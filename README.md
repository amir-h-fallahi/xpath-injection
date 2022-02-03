# XPath injection sample written in PHP
This sample is created to show XPath injection vulnerability, exploit and patch it.

## Base XPath Query
```sql
//users/user[username = '$username' and password = '$password']
```

## Exploit
- #### Bypass Authentication using supplied password
  Username: `admin` (or any valid username)

  Password: `' or username = 'admin`

  You can use this technique for both bypass authentication and username enumeration.
- #### Get password length
  Username: `admin`

  Password: `' or string-length(password) = 1 and username = 'admin`

  If admin password length equals to 1 character, you will be logged in (and receive status code 200) otherwise won't (and receive status code 401).
You should increase value password length until you get the correct reponse (status code 200).
- #### Extract password character by character
  Username: `admin`

  Password: `' or username = 'admin' and substring(password,1,1) = 'a`
  
  If the first character of the admin password equals to 'a' character, you will be logged in (and receive status code 200) otherwise won't (and receive status code 401).

**You could use python script [xpath-injection-exploit.py](xpath-injection-exploit.py) to exploit this target more easily.**
## Patch
The user input should be checked against a whitelist of acceptable characters.
The best approach is to reject any input that does not match the whitelist and not sanitize it therefore characters that may be used to interfere with the XPath query should be blocked, including `( ) [ ] ' = : , * /` .

In this sample uncomment line 23 & 24 to patch the vulnerability.

Patch at the code level: 
```php
function prevent_xpath_injection(string $input){
    $input = trim($input);
    if (preg_match('/[^a-zA-Z0-9]/',$input)){
        // input is malformed
        return false
    }
    else {
        return true;
    }
}
```
