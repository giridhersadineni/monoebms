import mysql.connector
server="127.0.0.1"
username="atech_ebmsarts"
password="giridhersadineni"
try:
    conn=mysql.connector.connect(host=server,user=username,password=password,database="atech_ebms")
    query="select * from rholdernew"
    cur=conn.cursor()
    result=cur.execute(query)
    masters=[]
    records=[]
    for row in cur:
        records.append(list(row))
        masters.append(row[-2])
    
    queries=open("giridher.csv","w")
    for row in records:
        row[-1]=str(masters.count(row[-2]))
        for r in row:
            queries.write(str(r)+",")   
        queries.write("\n")
    
    queries.close
    
    conn.commit()
    cur.close()    
    print("Length of Records",len(records))
    conn.close()
except mysql.connector.Error as err:
    print(err)

conn.close()

