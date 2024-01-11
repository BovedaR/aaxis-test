
# API Documentation

## Products
### Get All Products

**Endpoint:** `/api/products`

**Method:** `GET`

**Description:** Retrieves a list of all products from the database.

**Responses:**

  - **200 OK**

    If the request is successful, an array of products is returned.

    **Response Body:**

    ```json
        [
            {
            "id": 1,
            "sku": "ABC123",
            "name": "Product 1",
            "description": "Description of Product 1"
            },
            {
            "id": 2,
            "sku": "DEF456",
            "name": "Product 2",
            "description": "Description of Product 2"
            }
            // ... (more products)
        ]
    ```

  - **404 Not Found**

    If no products are found, a 404 status code is returned with a message.

    **Response Body:**
    ```json
        {
            "message": "No products found"
        }
    ```

  - **Other Responses**

    Additional error responses may occur for various reasons. Ensure to handle these cases appropriately and provide meaningful error messages.

    **Response Body:**

    ```json
        {
            "message": "Detailed error message"
        }
      ```

### Get Single Product

**Endpoint:** `/api/products/{id}`

**Method:** `GET`

**Description:** Retrieves details of a single product based on its ID.

**Parameters:**

- `{id}`: The unique identifier of the product.

**Responses:**

- **200 OK**

  If the product is found, its details are returned.

  **Response Body:**

    ```json
    {
        "id": 1,
        "sku": "ABC123",
        "name": "Product 1",
        "description": "Description of Product 1"
    }
    ```
- **404 Not Found**

  If the product with the specified ID is not found, a 404 status code is returned with a message.

  **Response Body:**

  ```json
  {
    "message": "Product with id {id} not found"
  }
  ```
- **Other Responses**

  Additional error responses may occur for various reasons. Ensure to handle these cases appropriately and provide meaningful error messages.

  **Response Body:**

  ```json
  {
    "message": "Detailed error message"
  }
  ```

### Create Products

**Endpoint:** `/api/products`

**Method:** `POST`

**Description:** Creates new products based on the provided data.

**Request Body:**

```json
[
  {
    "sku": "ABC123",
    "name": "Product 1",
    "description": "Description of Product 1"
  },
  {
    "sku": "DEF456",
    "name": "Product 2",
    "description": "Description of Product 2"
  }
  // ... (more products)
]
```
- **200 OK**

  If the products are successfully created, a success message is returned.

  **Response Body:**

  ```json
  {
    "message": "Products created successfully"
  }
  ```

- **400 Bad Request**

  If there are validation errors or if products with duplicate SKUs are detected, a 400 status code is returned with detailed error messages.

  **Response Body:**

  ```json
  {
    "message": [
      "Expecting mandatory parameters: sku",
      "Product with sku ABC123 already exists",
      // ... (more error messages)
    ]
  }
  ```

- **Other Responses**

  Additional error responses may occur for various reasons. Ensure to handle these cases appropriately and provide meaningful error messages.

  **Response Body:**

  ```json
  {
    "message": "Detailed error message"
  }
  ```

### Update Products

**Endpoint:** `/api/products`

**Method:** `PUT`

**Description:** Updates existing products based on the provided data.

**Request Body:**

```json
[
  {
    "id": 1,
    "sku": "UpdatedSKU1",
    "name": "Updated Product 1",
    "description": "Updated Description of Product 1"
  },
  {
    "id": 2,
    "sku": "UpdatedSKU2",
    "name": "Updated Product 2",
    "description": "Updated Description of Product 2"
  }
  // ... (more products to update)
]
```

- **200 OK**

  If the products are successfully updated, a success message is returned.

  **Response Body:**

  ```json
  {
    "message": "Products updated successfully"
  }
  ```

- **400 Bad Request**

  If there are validation errors or if products with duplicate SKUs are detected, a 400 status code is returned with detailed error messages.

  **Response Body:**

  ```json
  {
    "message": [
      "Expecting mandatory parameters: id for product UpdatedSKU1",
      "Product with sku UpdatedSKU1 already exists",
      // ... (more error messages)
    ]
  }
  ```

- **Other Responses**

  Additional error responses may occur for various reasons. Ensure to handle these cases appropriately and provide meaningful error messages.

  **Response Body:**

  ```json
  {
    "message": "Detailed error message"
  }
  ```

### Delete Product

**Endpoint:** `/api/products/{id}`

**Method:** `DELETE`

**Description:** Deletes a product based on its ID.

**Parameters:**

- `{id}`: The unique identifier of the product.

**Responses:**

- **200 OK**

  If the product is successfully deleted, a success message is returned.

  **Response Body:**

  ```json
  {
    "message": "Product deleted"
  }
  ```

- **404 Not Found**

  If the product with the specified ID is not found, a 404 status code is returned with a message.

  **Response Body:**

  ```json
  {
    "message": "Product with id {id} not found"
  }
  ```

- **Other Responses**

  Additional error responses may occur for various reasons. Ensure to handle these cases appropriately and provide meaningful error messages.

  **Response Body:**

  ```json
  {
    "message": "Detailed error message"
  }
  ```
  
## Auth
### Register Endpoint

**Endpoint:** `/auth/register`

**Method:** `POST`

**Description:** This endpoint is used to register a new user.

**Request Body:**

```json
{
  "username": "example_username",
  "email": "example@email.com",
  "password": "example_password_in_base64"
}
```
**Responses:**

- **200 OK**

  If the user is successfully registered, a JWT token is returned.

  **Response Headers:**

  | Field        | Type   | Description                  |
  | ------------ | ------ | ---------------------------- |
  | Authorization| string | The JWT token.               |

  **Response Body:**

  | Field   | Type   | Description                  |
  | ------- | ------ | ---------------------------- |
  | message | string | The success message.         |

- **400 Bad Request**

  If the username or email already exists, or if the username, email, or password is not provided, a 400 status code is returned with a message.

  **Response Body:**

  | Field   | Type   | Description                  |
  | ------- | ------ | ---------------------------- |
  | message | string | The error message.           |

### Login Endpoint

**Endpoint:** `/auth/login`

**Method:** `POST`

**Description:** This endpoint is used to authenticate a user and return a JWT token.

**Request Body:**

```json
{
  "username": "example_username",
  "password": "example_password_in_base64"
}
```

**Responses:**

- **200 OK**

  If the username and password are correct, a JWT token is returned.

  **Response Headers:**

  | Field        | Type   | Description                  |
  | ------------ | ------ | ---------------------------- |
  | Authorization| string | The JWT token.               |

  **Response Body:**

  | Field | Type   | Description                  |
  | ----- | ------ | ---------------------------- |
  | token | string | The JWT token.               |

- **400 Bad Request**

  If the username or password is not provided, a 400 status code is returned with a message.

  **Response Body:**

  | Field   | Type   | Description                  |
  | ------- | ------ | ---------------------------- |
  | message | string | The error message.           |

- **401 Unauthorized**

  If the username or password is incorrect, a 401 status code is returned with a message.

  **Response Body:**

  | Field   | Type   | Description                  |
  | ------- | ------ | ---------------------------- |
  | message | string | The error message.           |

### Refresh Token Endpoint

**Endpoint:** `/auth/refresh`

**Method:** `POST`

**Description:** This endpoint is used to refresh the JWT token for a user.

**Request Body:**

```json
{
  "username": "example_username",
  "password": "example_password_in_base64"
}
```

**Responses:**

- **200 OK**

  If the username and password are correct, a new JWT token is returned.

  **Response Headers:**

  | Field        | Type   | Description                  |
  | ------------ | ------ | ---------------------------- |
  | Authorization| string | The new JWT token.           |

  **Response Body:**

  | Field | Type   | Description                  |
  | ----- | ------ | ---------------------------- |
  | token | string | The new JWT token.           |

- **400 Bad Request**

  If the username or password is not provided, a 400 status code is returned with a message.

  **Response Body:**

  | Field   | Type   | Description                  |
  | ------- | ------ | ---------------------------- |
  | message | string | The error message.           |

- **401 Unauthorized**

  If the username or password is incorrect, a 401 status code is returned with a message.

  **Response Body:**

  | Field   | Type   | Description                  |
  | ------- | ------ | ---------------------------- |
  | message | string | The error message.           |