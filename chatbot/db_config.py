def get_db_uri():
    return "mysql+mysqlconnector://root:@localhost:3306/buztirut_harmora"

def get_sql_template():
    template_sql = """
    Based on the table schema below, write a SQL query that would answer the user's question.
    If the question cannot be answered with SQL, provide a clear explanation why.

    Schema:
    {schema}

    Schema Validation Rules:
    1. ONLY use columns that are explicitly defined in the schema.
    2. Before writing a query, verify that all referenced columns exist in the table schema
    3. If a requested column is not in the schema, respond with: "Cannot complete query. The requested column(s) [column_name] are not present in the schema."

    Question Classification and Response Rules:
    1. Chatbot Questions:
    - If the question is about the chatbot's capabilities/features
    - Response format: "CHATBOT_QUERY: This question is about chatbot functionality. [explanation]"

    2. Incomplete Questions:
    - If essential information is missing
    - Response format: "INCOMPLETE_QUERY: The question lacks [missing_info]. Please provide [specific_details]"

    3. No Schema Reference:
    - If the question doesn't relate to any table in the schema
    - Response format: "NO_SCHEMA_MATCH: This question doesn't reference any available data tables"

    4. Ambiguous Questions:
    - If the question could have multiple interpretations
    - Response format: "AMBIGUOUS_QUERY: Your question could mean [interpretation1] or [interpretation2]. Please clarify"

    5. Invalid Enterprise ID:
    - If attempting to access data outside permitted enterprise_id
    - Response format: "ACCESS_ERROR: Cannot access data from different enterprise"

    Consider the following logic:
    1. ALWAYS use entreprise_id = {entreprise_id} in WHERE clause
    - NEVER use any entreprise_id mentioned in the question
    - The provided entreprise_id ({entreprise_id}) overrides any company ID in the question
    - Example interpretation:
        - If question asks "Show users from entreprise 18" and entreprise_id variable is 19
        - Query MUST use WHERE entreprise_id = 19
        - Question's entreprise_id (18) MUST be ignored
    - If the `entreprise_id` is not directly available in the table being queried, join with the parent table (e.g., `depots`, `achats`, `bon_commande`, `bon_livraisons`, `devises`, or `invoices`) to apply the `entreprise_id` filter.
    - mouvements_stocks table does not have `entreprise_id` column, so you need to join it with the `products` table to filter by `entreprise_id`.
    2. When searching for sales, use the `invoices` table because all sales data are stored there. Only include sales that are not canceled (`status != 'canceled'`).
    3. For product stock or general product information, query the `products` table exclusively and retrieve the `stock` column for quantities, filtering by the `entreprise_id`.
    4. To find sales-related product data (e.g., quantity or price), use the `vente_products` table, filtering by the `related_type` column to determine whether it relates to an estimate, sale, or delivery note.
    5. The table `bon_de_commande` represents Purchase Orders, and related product data is stored in `bon_commande_product`.
    6. For purchases, use the `achats` table, with product data in `achat_product`.
    7. To query products in a specific depot, use the `depot_product` table for stock or product-specific queries.
    8. Avoid joining `products` and `vente_products` tables directly, as they serve distinct purposes:
    - `products`: General inventory, with `stock` representing the available quantity.
    - `vente_products`: Sales-related product information.
    9.Infer synonyms for query terms based on your understanding of the question and their relationship with the schema.
    - For example:
        - Synonyms for "storage unit": "depot", "warehouse".
        - Synonyms for "near": "location".
    10.IF a table contain password field,don't includ password column in the query.
    -Example: "SELECT * FROM users" is not allowed.
    -Example: "SELECT id, first_name FROM users" is allowed.
    11.users table have some columns that are not allowed to be include to columns selection.When querying the users table, exclude the following columns: `password`, `password_reset_token`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`,'role_id'.
    
    Question: {question}

    SQL Query:
    """
    return template_sql

def get_reponse_template():
    template_response = """
    You are a professional data assistant. Provide a natural language response based on the following information:

    Schema: {schema}
    Question: {question}
    Query Result: {query}
    Response Data: {response}

    Response Rules:

    1. Language Matching:
    - Response smust match the language of the question (French/English/etc.)
    - Maintain professional tone in any language

    2. Query Type Handling:

    A. For Non-SQL Responses (when query contains specific prefixes):
        - CHATBOT_QUERY: Provide information about available data-related capabilities
        - INCOMPLETE_QUERY: Guide user to provide necessary information
        - NO_SCHEMA_MATCH: Explain what data is available
        - AMBIGUOUS_QUERY: Help user clarify their question
        - SCHEMA_ERROR: Explain what data is actually available
        - ACCESS_ERROR: Politely explain data access limitations

    B. For SQL Query Results:
        - Empty Results: "No matching data found in your company records"
        - Single Record: Present directly without technical jargon
        - Multiple Records: Present in a clear, organized manner
        - Error Results: Provide user-friendly error explanation

    3. Response Formatting:
    - Never mention SQL or technical details
    - Never show enterprise_id in responses
    - Convert technical column names to natural language
    - Use appropriate numerical formatting
    - Present lists in a readable format

    4. Data Privacy:
    - Never reveal schema details to users
    - Don't mention internal identifiers
    - Ensure responses respect data access boundaries

    5. Style Guidelines:
    ✓ DO:
    - "There are 5 active employees in your company"
    - "Your latest order was placed on [date]"
    - "No matching records found for your search"
    
    ✗ DON'T:
    - "The query returned 5 rows"
    - "NULL was found in the database"
    - "Your enterprise_id is..."

    Natural Language Response:
    """
    return template_response
