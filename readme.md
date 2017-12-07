# Some API 🛰

### Description
This project is an example of implementation REST API using Laravel,
[JWT Auth by tymon](https://github.com/tymondesigns/jwt-auth), PHPUnit, 
and actually I use [Laradock](http://laradock.io/getting-started/) to make it run on my localhost
with docker... As database I use MySQL in the docker container. 
Laradock was added as git submodule that's why u can't see its config. 
But at the same time you absolutely free to use something like Homestead to run the project. 
Have a nice day!

### API documentation

#### Users methods
##### Login
`POST /login`

*body:*
```text
{
  email: string,
  pssword: string
}
```

*response:*
```text
STATUS 200
{
    token: string
}

STATUS 403
Wrong credentials
```

##### Logout
`POST /logout`

*body:*
```text
{
    token: string
}
```

*response:*
```text
STATUS 200

STATUS 401
User not authorized
```
**If you have logged in, then for all methods which you call you have to do two things:**
* **Set the header that contains your token for each call: `Authorization: Bearer the_token_here`**
* **For each response make the check for field named "refreshed-token" to refresh your one timely**

#### Categories methods

##### Create category

`POST /categories`

*body:*
```text
{
    name: string
}
```

*response:*
```text
STATUS 201
Headers:
    Location: id_of_created_category

STATUS 401
User not authorized, or not an admin

STATUS 403
Category with the same name already exists
```

##### Update category

`PUT /categories/{id}`

*body:*
```text
{
    name: string
}
```

*response:*
```text
STATUS 200

STATUS 401
User not authorized, or not an admin or an editor

STATUS 404
Wrong ID

STATUS 403
Category with the same name already exists 
```

##### Delete category

`DELETE /categories/{id}`

*response:*
```text
STATUS 200


STATUS 401
User not authorized, or not an admin

STATUS 404
```

#### Posts methods

##### List all posts

`GET /posts`
`GET /posts?orderby=date&order=asc`

orderby:
* date
* category

order:
* asc
* desc

*response:*
```text
STATUS 200
{
    posts: [
        {
            id: integer,
            header: string,
            content: string,
            category: {
                id: integer,
                name: string
            }
        },
        ...
    ]
}
```

##### Create post

`POST /posts`

*body:*
```text
{
    header: string,
    content: string,
    category-id: integer
}
```

*response:*
```text
STATUS 201
Headers:
    Location: id_of_created_post

STATUS 401
User not authorized, or not an admin
```


##### Get the post

`GET /posts/{id}`

*response:*
```text
STATUS 200
{
    header: string,
    content: string,
    category: {
        id: integer,
        name: string
    }
}

STATUS 404

STATUS 401
User not authorized
```

##### Update post

`PUT /posts/{id}`

*body:*
```text
{
    header: string,
    content: string,
    category-id: integer
}
```

*response:*
```text
STATUS 200

STATUS 401
User not authorized, or not an admin or an editor

STATUS 404
Wrong ID
```

##### Delete post

`DELETE /posts/{id}`

*response:*
```text
STATUS 200

STATUS 401
User not authorized, or not an admin

STATUS 404
```