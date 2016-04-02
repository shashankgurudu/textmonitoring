#!flask/bin/python
from flask import Flask,jsonify,abort,make_response
from flask import request
import sentiment
app = Flask(__name__)


@app.errorhandler(404)
def not_found(error):
    return make_response(jsonify({'error': 'Not found'}), 404)

@app.route('/todo/api/v1.0/tasks/<int:task_id>',methods=['GET'])
def get_tasks(task_id):
    task = [task for task in tasks if task['id'] == task_id]
    if len(task) == 0:
        abort(404)
    return jsonify({'task': task[0]})

@app.route('/')
def index():
    return "Hello, World!"

@app.route('/keyword/<string:input_string>',methods=['GET'])
def getkeyword(input_string):
        #tokens = stopwordelimination(input_string)
        #tags = pos(tokens)
        #return jsonify({"nouns of sentence":tags})
        sentiment.TweepyService(input_string)

if __name__ == '__main__':
	app.run(host='0.0.0.0')

