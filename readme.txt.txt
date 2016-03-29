Exercise 2 - Read me
-------------------------------------------------------------------
1: connected to Mysql database
2: SQL file of the database is included in the folder for reference (table.sql)
3: created a test REST api(rest.php) to implement data manipulation functions  (createData,fetchDataById,searchData)
4: The test REST api uses same database schema
5: To see the output, please do the below mentioned code changes

-------------------------------------------------------------------
Code changes needed at lines:
1: index.php - lines 22 - 25
   update database connection variables

2: index.php - line 28
   update database table name

3: index.php - line 98
   update database table name

4: index.php - line 99
   update REST api URL

5: rest.php - line 8
   update database connection variables

-------------------------------------------------------------------
Output:
1: Sample data is provided at lines 213 - 215
2: To test PDO uncomment lines 218 - 221
3: To test REST uncomment lines 224 - 227