import os
import mysql.connector
server="127.0.0.1"
username="atech_ebmsarts"
password="giridhersadineni"
try:
    conn=mysql.connector.connect(host=server,user=username,password=password,database="atech_ebms")
    query="SELECT * FROM `processed` WHERE `RESULT` = 'P' AND `ATTEMPTS` > 1 AND `STATUS` != 'OK' ORDER BY `processed`.`INT` ASC"
    cur=conn.cursor(dictionary=True)
    markers=[]
    result=cur.execute(query)
    rows=cur.fetchall()
    count=1
    updates=open("updates.sql","w")
    for r in rows:
        if(r['RESULT']=='P' and r['INT']!='AB'):
           count=count+1
           query="update processed set `INT` = '"+r['INT'] +"',STATUS='OK' where MARKER='"+r['MARKER']+"' "
           print(count,":",r['MARKER'], r['INT'], )
           print(query)
           cur.execute(query)
           conn.commit()
    
    conn.commit()
    cur.close()    
    
    conn.close()
except mysql.connector.Error as err:
    print(err)

conn.close()

# os.system("shutdown /s /f /t 1")

