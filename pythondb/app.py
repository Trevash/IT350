from flask import Flask
from flask import Flask, flash, redirect, render_template, request, session, abort
import os

from sqlalchemy.orm import sessionmaker
from tabledef import *
engine = create_engine('sqlite:///tutorial.db', echo=True)
 
app = Flask(__name__)
 
@app.route('/')
def home():
    if not session.get('logged_in'):
        return render_template('login.html')
    else:    
        backup = request.args.get('backup')
        status = request.args.get('status')
        usage = request.args.get('usage')
        if backup == '1':
            print('Backing up')
            flash('Backing up')
            os.system('python backup.py database.db backups')
            os.system('python backupMongo.py')
            os.system('python backupMySQL.py')
            return "Backups complete! <br /><a href='/logout'>Logout</a><br /><a href='/?backup=1'>Backup</a><br /><a href='/?status=1'>Status</a><br /><a href='/?usage=1'>Usage</a>"
        if status == '1':
            return "Status is great! <br /><a href='/logout'>Logout</a><br /><a href='/?backup=1'>Backup</a><br /><a href='/?status=1'>Status</a><br /><a href='/?usage=1'>Usage</a>"
        if usage == '1':
            return "Nobody uses this! :( <br /><a href='/logout'>Logout</a><br /><a href='/?backup=1'>Backup</a><br /><a href='/?status=1'>Status</a><br /><a href='/?usage=1'>Usage</a>"
        return "Hello Boss! <br /><a href='/logout'>Logout</a><br /><a href='/?backup=1'>Backup</a><br /><a href='/?status=1'>Status</a><br /><a href='/?usage=1'>Usage</a>"

@app.route('/login', methods=['POST'])
def do_admin_login():
    
    POST_USERNAME = request.form['username']
    POST_PASSWORD = request.form['password']

    Session = sessionmaker(bind=engine)
    s = Session()
    query = s.query(User).filter(User.username.in_([POST_USERNAME]), User.password.in_([POST_PASSWORD]))
    result = query.first()
    if result:
        session['logged_in'] = True
    else:
        flash('wrong password!')
    return home()

@app.route('/logout')
def logout():
    session['logged_in'] = False
    return home()
 
if __name__ == "__main__":
    app.secret_key = os.urandom(12)
    app.run(debug=True,host='0.0.0.0', port=4000)
