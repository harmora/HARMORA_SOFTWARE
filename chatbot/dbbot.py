from flask import Flask, request, jsonify
import os
from db_config import get_sql_template, get_reponse_template, get_db_uri
from langchain_core.prompts import ChatPromptTemplate
from langchain_community.utilities import SQLDatabase
from langchain_core.output_parsers import StrOutputParser
from langchain_core.runnables import RunnablePassthrough
from langchain_openai import ChatOpenAI
from typing import Dict, Any

app = Flask(__name__)

# Configuration
OPENAI_API_KEY = os.getenv("OPENAI_API_KEY")

# Database setup
db_url = get_db_uri()
db = SQLDatabase.from_uri(db_url)

# Templates setup
template_sql = get_sql_template()
template_response = get_reponse_template()

prompt_sql = ChatPromptTemplate.from_template(template_sql)
prompt_response = ChatPromptTemplate.from_template(template_response)

# Initialize OpenAI
llm = ChatOpenAI()

# functions
def get_schema(_):
    return db.get_table_info()

def run_query(query):
    query = query.strip()
    if query.endswith(';'):
        query = query[:-1]
    try:
        result = db.run(query)
        return result
    except Exception as e:
        raise Exception(f"Query execution error: {str(e)}")

def validate_sql_query(query: str) -> bool:
    """
    Basic SQL validation - checks if the string contains basic SQL keywords
    This is a simple example - you might want to use a proper SQL parser
    """
    sql_keywords = ['SELECT', 'FROM']
    query_upper = query.upper()
    return any(keyword in query_upper for keyword in sql_keywords)

def safe_run_query(variables: Dict[str, Any]) -> str:
    """
    Wrapper around run_query that includes SQL validation
    """
    query = variables.get("query", "")
    if not validate_sql_query(query):
        return "Invalid SQL query: The provided text does not appear to be a valid SQL query."
    try:
        return run_query(query)
    except Exception as e:
        return f"Error executing query: {str(e)}"

# Set up chains
sql_chain = (
    RunnablePassthrough.assign(schema=get_schema)
    | prompt_sql
    | llm.bind(stop=["\nSQLResult:"])
    | StrOutputParser()
)

# full_chain = (
#     RunnablePassthrough.assign(query=sql_chain).assign(
#         schema=get_schema,
#         response=lambda vars: run_query(vars["query"]),
#     )
#     | prompt_response
#     | llm
#     | StrOutputParser()
# )
full_chain = (
    RunnablePassthrough.assign(query=sql_chain).assign(
        schema=get_schema,
        response=safe_run_query,
    )
    | prompt_response
    | llm
    | StrOutputParser()
)

@app.route('/')
def home():
    return '''
    <html>
        <head>
            <title>Database Query Interface</title>
        </head>
        <body>
            <h1>Database Connection Example</h1>
            <form action="/query" method="POST">
                <input type="text" name="question" placeholder="Ask a question...">
                <input type="submit" value="Fetch Data">
            </form>
        </body>
    </html>
    '''
@app.route('/chat', methods=['POST'])
def chat():
    message = request.json['message']
    entreprise_id = request.json['entreprise_id']
    if not message:
        return jsonify({'error': 'No message provided'}), 400
    

    
    # Process the message using your AI model
    res =response = full_chain.invoke({
            "question": message,
            "entreprise_id": entreprise_id
        })    
    return jsonify({'response': res})

# @app.route('/query', methods=['POST'])
# def query():
#     try:
#         question = request.form.get('question')
#         if not question:
#             return jsonify({'error': 'No question provided'}), 400

#         # Execute the full chain
#         response = full_chain.invoke({
#             "question": question,
#             "entreprise_id": entreprise_id
#         })

#         return f'''
#         <html>
#             <head>
#             <title>Query Result</title>
#             </head>
#             <body>
#             <h1>Query Result</h1>
#             <p>{response}</p>
#             <a href="/">Go back</a>
#             </body>
#         </html>
#         '''
#     except Exception as e:
#         return jsonify({'error': str(e)}), 500

# @app.route('/api/query', methods=['POST'])
# def api_query():
#     try:
#         data = request.get_json()
#         if not data or 'question' not in data:
#             return jsonify({'error': 'No question provided in JSON body'}), 400

#         # Execute the full chain
#         response = full_chain.invoke({
#             "question": data['question'],
#             "entreprise_id": entreprise_id
#         })

#         return jsonify({
#             'response': response
#         })

#     except Exception as e:
#         return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)