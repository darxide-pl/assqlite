# assqlite

"Backdoor to remotely hosted sqlite databases"<br>

Notice: this is not a perfect solution, not at all. This is some kind of quick, weird workaround.<br>

REQUIREMENTS:<br>
Your remote server must support sqlite3<br>

HOW TO USE:<br>
1) Copy contents of folder named "server" to remote host<br>
2) Copy your sqlite database to the same folder.<br>
3) In your script include client/assqlite.class.php<br>

Full functionality was presented on index.php <br>

Init:<br>
```php 
require_once('client/assqlite.class.php');
$db = new assqlite("http://example.com/path_to_folder", "example.db");
```

Methods:<br>
assqlite::busyTimeout(int $ms);<br>
Set busy timeout duration, or disable busy handlers<br>
```php
$db->busyTimeout(1234);
```

assqlite::exec(string $query);<br>
Executes a result-less query against a given database<br>
```php 
$db->exec(" DELETE FROM `users` WHERE id > 100 ");
```

assqlite::query(string $query);<br>
Executes an SQL query<br>
```php 
$results = $db->query("SELECT * FROM `users` LIMIT 13");
```

assqliteResult::num_rows();<br>
Returns number of rows;
```php 
$rows = $results->num_rows();
```

assqliteResult::fetchObject();<br>
Returns result row as object
```php 
while($row = $results->fetchObject()){
  print_r($row->{'id'});
}
```

assqliteResult::fetchArray();<br>
Returns result row as array
```php 
while($row = $results->fetchArray()){
	print_r($row['id']);
}
```

assqliteResult::data_seek(int $offset);<br>
Move result array to declared point
```php
$results->data_seek(10);
```


