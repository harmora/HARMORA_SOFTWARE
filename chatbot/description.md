1. create virtual envirement venv and install requirements.txt 
2. go to nvidia website : https://build.nvidia.com/explore/discover
3. create an account/ login 
4. go to this  page : https://build.nvidia.com/meta/llama-3_1-70b-instruct
5. create an api key by cliquing the buttun 'get api key' -> generate key -> copie the key
6. create .env file and add NVIDIA_API_KEY="make the key generated here"
7. run the project and check the port wich run on the server and verify if it's the same in the ChatBotController chat function 'localhost:port'
8. to run the python project: flask run --port=5001 <<!--here i add  --port=5001 cause default port 5000 already used>>