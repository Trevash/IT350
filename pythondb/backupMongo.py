import pymongo
import json
import io
from bson.json_util import dumps
from pymongo import MongoClient

client = MongoClient()
print client
db = client['testForAuth']
collection = db['users']

obj = dumps(collection.find({}))

with io.open('backups/backupMongo.txt', 'wb') as f:
    json.dump(obj, f, ensure_ascii=False)
