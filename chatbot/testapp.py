from flask import Flask, request, jsonify
from langchain.chains.history_aware_retriever import create_history_aware_retriever
from langchain.chains.retrieval import create_retrieval_chain
from langchain.chains.combine_documents import create_stuff_documents_chain
from langchain_community.embeddings import HuggingFaceEmbeddings
from langchain_community.llms import HuggingFaceHub
from langchain_community.llms import HuggingFacePipeline
from langchain_chroma import Chroma
from langchain_community.document_loaders import TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.prompts.chat import MessagesPlaceholder
from langchain_core.messages import HumanMessage, AIMessage
from transformers import AutoModelForCausalLM, AutoTokenizer, pipeline

from typing import List
import os
import dotenv


app = Flask(__name__)

dotenv.load_dotenv()
os.environ["HUGGINGFACEHUB_API_TOKEN"] = os.getenv("HUGGINGFACEHUB_API_TOKEN")

# Using smaller, efficient open source models
MODEL_NAME = "facebook/opt-125m"  # or "facebook/opt-350m"
EMBEDDING_MODEL = "sentence-transformers/all-MiniLM-L6-v2"
VECTOR_DB = "./vector_db"
COLLECTION_NAME = "stage-faq"

# Rest of your prompts remain the same
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

def get_llm():
    # Initialize tokenizer and model
    tokenizer = AutoTokenizer.from_pretrained(MODEL_NAME)
    model = AutoModelForCausalLM.from_pretrained(MODEL_NAME)
    
    # Create pipeline
    pipe = pipeline(
        "text-generation",
        model=model,
        tokenizer=tokenizer,
        max_length=512,
        temperature=0.7,
        top_p=0.95,
        repetition_penalty=1.15
    )
    
    # Create LangChain LLM
    llm = HuggingFacePipeline(pipeline=pipe)
    return llm

def get_embeddings():
    return HuggingFaceEmbeddings(
        model_name=EMBEDDING_MODEL,
        model_kwargs={'device': 'cpu'}
    )
    
def get_vector_store():

    return Chroma(
        collection_name=COLLECTION_NAME,
        embedding_function=get_embeddings(),
        persist_directory=VECTOR_DB,
    )


def load_data_and_index():
    # Create text splitter
    text_splitter = RecursiveCharacterTextSplitter(
        chunk_size=1000,
        chunk_overlap=200,
        length_function=len,
    )
    
    # Load and split the documents
    loader = TextLoader("questions_reponses.txt")
    documents = loader.load()
    splits = text_splitter.split_documents(documents)
    
    # Initialize vector store with the correct embeddings
    vector_store = get_vector_store()
    
    # Add documents to the vector store
    vector_store.add_documents(splits)
    vector_store.persist()
    return vector_store

def get_rag_chain():
    llm = HuggingFaceHub(
        repo_id=MODEL_NAME,
        model_kwargs={
            "temperature": 0.5,
            "max_length": 512,
            "top_p": 0.95
        }
    )
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

# Rest of your code remains the same
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

# if __name__ == '__main__':
#     app.run(debug=True)
if __name__ == '__main__':
    load_data_and_index()
    app.run(debug=True)
