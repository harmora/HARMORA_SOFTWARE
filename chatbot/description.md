1. create virtual envirement venv and install requirements.txt 
2. go to nvidia website : https://build.nvidia.com/explore/discover
3. create an account/ login 
4. go to this  page : https://build.nvidia.com/meta/llama-3_1-70b-instruct
5. create an api key by cliquing the buttun 'get api key' -> generate key -> copie the key
6. create .env file and add NVIDIA_API_KEY="make the key generated here"
7. run the project and check the port wich run on the server and verify if it's the same in the ChatBotController chat function 'localhost:port'
8. to run the python project: flask run --port=5001 <<!--here i add  --port=5001 cause default port 5000 already used>>

for db chatbot in dbbot.py:
1. create an openai acoount
2. go to https://platform.openai.com/settings/organization/billing/overview and by credits
3. create an api key https://platform.openai.com/settings/organization/api-keys
4. copy it and in .env file create an OPENAI_API_KEY variable 
5. OPENAI_API_KEY="api_key_copied"
6. use harmoravenv and run the project using  flask --app dbbot.py run --port=5002 --reload

For db_config.py contain the templates for sqlprompt and responseprompt