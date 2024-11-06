from flask import Flask, request, jsonify
from langchain.chains.history_aware_retriever import create_history_aware_retriever
from langchain.chains.retrieval import create_retrieval_chain
from langchain.chains.combine_documents import create_stuff_documents_chain
from langchain_chroma import Chroma
from langchain_nvidia_ai_endpoints import NVIDIAEmbeddings, ChatNVIDIA
from langchain_community.document_loaders import TextLoader
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.prompts.chat import MessagesPlaceholder
from langchain_core.messages import HumanMessage, AIMessage
from typing import List
from requests.exceptions import ConnectionError
import os
import dotenv

app = Flask(__name__)

dotenv.load_dotenv()
os.environ["NVIDIA_API_KEY"] = os.getenv("NVIDIA_API_KEY")

MODEL_NAME = "meta/llama-3.1-70b-instruct"
EMBEDDING_MODEL = "nvidia/nv-embed-v1"
VECTOR_DB = "./vector_db"
COLLECTION_NAME = "stage-faq"
SYSTEM_PROMPT = (
    "You are an assistant for question-answering tasks."
    "Use the following pieces of retrieved context to answer "
    "the question. If you don't know the answer, say that you "
    "don't know. Use three sentences maximum and keep the "
    "answer concise."
    "\n\n"
    "{context}"
)
CONTEXTUALIZE_Q_SYSTEM_PROMPT = (
    "Given a chat history and the latest user question "
    "which might reference context in the chat history, "
    "formulate a standalone question which can be understood "
    "without the chat history. Do NOT answer the question, "
    "just reformulate it if needed and otherwise return it as is."
)


def get_vector_store():
    return Chroma(
        collection_name=COLLECTION_NAME,
        embedding_function=NVIDIAEmbeddings(model=EMBEDDING_MODEL),
        persist_directory=VECTOR_DB,
    )


def load_data_and_index():
    loader = TextLoader("questions_reponses.txt")
    docs = loader.load_and_split()
    vector_db = get_vector_store()
    vector_db.add_documents(docs)


def get_rag_chain():
    llm = ChatNVIDIA(model=MODEL_NAME)
    retriever = get_vector_store().as_retriever()
    contextualize_q_prompt = ChatPromptTemplate.from_messages(
        [
            ("system", CONTEXTUALIZE_Q_SYSTEM_PROMPT),
            MessagesPlaceholder("chat_history"),
            ("human", "{input}"),
        ]
    )
    history_aware_retriever = create_history_aware_retriever(
        llm, retriever, contextualize_q_prompt
    )
    qa_prompt = ChatPromptTemplate.from_messages(
        [
            ("system", SYSTEM_PROMPT),
            MessagesPlaceholder("chat_history"),
            ("human", "{input}"),
        ]
    )
    question_answer_chain = create_stuff_documents_chain(llm, qa_prompt)
    rag_chain = create_retrieval_chain(history_aware_retriever, question_answer_chain)
    return rag_chain


def get_chat_history(messages):
    chat_history = []
    for msg in messages:
        chat_history.append(HumanMessage(content=msg["human"]))
        chat_history.append(AIMessage(content=msg["ai"]))
    return chat_history


def ask_ai(query: str, messages):
    chat_history = get_chat_history(messages)
    rag_chain = get_rag_chain()
    input = {"input": query, "chat_history": chat_history}
    return rag_chain.invoke(input)


chat_history = []

@app.route("/")
def hello_world():
    return "<p>Hello, chat service!</p>"


@app.route('/chat', methods=['POST'])
def chat():
    global chat_history
    message = request.json['message']
    
    # Process the message using your AI model
    res = ask_ai(message, chat_history)
    
    # Update chat history
    chat_history.append({"human": message, "ai": res["answer"]})
    
    return jsonify({'response': res["answer"]})

if __name__ == '__main__':
    app.run(debug=True)
if __name__ == '__main__':
    # load_data_and_index()
    app.run(debug=True)
