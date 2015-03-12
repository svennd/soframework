## Introduction ##
| Using a language as PHP, we are forced to make some guidelines, as every developer has his own coding style, this wouldn't work. Therefore we show you what our standards are. |
|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|

### 1. Operators ###
+, -, =, !=, ==, ect should have space before and after

ex.
```
$foo = $bar;
$a == $b
```

only exeption is ++ and --
```
$var--;
$b++;
```

### 2. control structures ###

#### 2.1 if/else statement ####
```
if ( condition )
{
	// action, 1 tab
}
elseif ( condtion )
{
	// action, 1 tab
}
else
{
	// action
}
```
#### 2.2 switch statement ####
```
switch ( condition ) 
{
	case 1:
		//action
		break;
	default:
		//action
}
```
### 3. loops ###
its proven that do-while, for, and such all can be rewritten into while loops.
Therefore while loops are prefered, foreach and for is allowed aswell.
```
$i = 0;
while ( $i => $par )
{
	$i++; // action, 1 tab
}
```

```
for($i = 0; $i => $par; $i++ )
{
	// action, 1 tab
}
```
### 4. functions ###
#### 4.1 declaration ####
```
function name ( par, par2 )
{
	// actions	
}
```

#### 4.2 calling functions ####
```
$save = name( par, par2 );
```

### 5. Arrays ###
```
$var = array ( a, b, c, d );
$var = array (
		a => b,
		c => d,
		);
```

### 6. string handeling ###

string should only be split if line is to long. Use the point to merge 2 strings
```
$test = 'hel'. $lo .' world';
```

### 7. comments ###
we prefer the use of # as it gives less chance of deleting one of the two more populair '//'

This version of coding standards is based on : [drupal coding standards](http://drupal.org/coding-standards)