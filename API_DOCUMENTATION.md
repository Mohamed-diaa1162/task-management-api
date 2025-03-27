# API Documentation

This document provides a comprehensive guide to the Task Management API endpoints, including request formats, response structures, and authentication requirements.

## Base URL

All API endpoints are accessible through the base URL:

```
http://localhost:8000/api/v1
```

For production environments, replace `localhost:8000` with your domain.

## Authentication

The API uses JWT (JSON Web Token) for authentication. Most endpoints require a valid token, which should be included in the `Authorization` header.

### How to Obtain a Token

1. **Register a new user** (if you don't have an account already):

   ```
   POST /auth/users
   ```

   Request Body:
   ```json
   {
     "name": "John Doe",
     "email": "john@example.com",
     "password": "secret_password",
     "password_confirmation": "secret_password"
   }
   ```

   Response:
   ```json
   {
     "status": "success",
     "message": "User registered successfully",
     "data": {
       "user": {
         "id": 1,
         "name": "John Doe",
         "email": "john@example.com",
         "created_at": "2023-03-27T12:34:56.000000Z"
       }
     }
   }
   ```

2. **Login to obtain a token**:

   ```
   POST /auth/login
   ```

   Request Body:
   ```json
   {
     "email": "john@example.com",
     "password": "secret_password"
   }
   ```

   Response:
   ```json
   {
     "status": "success",
     "message": "Login successful",
     "data": {
       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
       "token_type": "bearer",
       "expires_in": 3600
     }
   }
   ```

### Using the Token

Include the token in the `Authorization` header for authenticated requests:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

### Token Renewal and Logout

- **Refresh token**:
  ```
  POST /auth/refresh
  ```

- **Logout**:
  ```
  POST /auth/logout
  ```

## Error Handling

The API returns appropriate HTTP status codes along with JSON responses for errors:

- `400 Bad Request` - Malformed request or validation errors
- `401 Unauthorized` - Authentication required or failed
- `403 Forbidden` - Not authorized to access the resource
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation errors
- `429 Too Many Requests` - Rate limit exceeded
- `500 Internal Server Error` - Server-side errors

Example error response:
```json
{
  "status": "error",
  "message": "The given data was invalid",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

## Pagination

List endpoints return paginated results. The response includes metadata about the pagination state:

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "per_page": 15,
    "to": 15,
    "total": 40
  },
  "links": {
    "first": "http://localhost:8000/api/v1/tasks?page=1",
    "last": "http://localhost:8000/api/v1/tasks?page=3",
    "prev": null,
    "next": "http://localhost:8000/api/v1/tasks?page=2"
  }
}
```

You can control pagination using these query parameters:
- `page`: The page number (default: 1)
- `per_page`: Number of items per page (default: 15, max: 100)

## API Endpoints

### User Management

#### Get Current User

```
GET /auth/me
```

Response:
```json
{
  "status": "success",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2023-03-27T12:34:56.000000Z",
      "updated_at": "2023-03-27T12:34:56.000000Z"
    }
  }
}
```

#### Update User Profile

```
PUT /auth/users
```

Request Body:
```json
{
  "name": "John Updated",
  "email": "john.updated@example.com",
  "current_password": "current_password",
  "password": "new_password",
  "password_confirmation": "new_password"
}
```

Note: `password` fields are optional. Include them only if you want to update the password.

### Tasks

#### List Tasks

```
GET /tasks
```

Query Parameters:
- `status`: Filter by status (1 = Pending, 2 = In Progress, 3 = Completed)
- `search`: Search term for title/description
- `start_date`: Filter by tasks created after this date (Y-m-d)
- `end_date`: Filter by tasks created before this date (Y-m-d)
- `sort`: Sort field (created_at, updated_at, title, status)
- `direction`: Sort direction (asc, desc)

Response:
```json
{
  "data": [
    {
      "id": 1,
      "title": "Complete project documentation",
      "description": "Write comprehensive documentation for the project",
      "status": 1,
      "due_date": "2023-04-15 12:00:00",
      "created_at": "2023-03-27T12:34:56.000000Z",
      "updated_at": "2023-03-27T12:34:56.000000Z",
      "assigned_users": [
        {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 15,
    "to": 1,
    "total": 1
  },
  "links": {
    "first": "http://localhost:8000/api/v1/tasks?page=1",
    "last": "http://localhost:8000/api/v1/tasks?page=1",
    "prev": null,
    "next": null
  }
}
```

#### Create Task

```
POST /tasks
```

Request Body:
```json
{
  "title": "New Task",
  "description": "This is a new task",
  "status": 1,
  "due_date": "2023-05-30 15:00:00",
  "assigned_to": [1, 2]
}
```

Fields:
- `title` (required): Task title (max: 255 characters)
- `description` (required): Task description
- `status` (optional): Task status (1 = Pending, 2 = In Progress, 3 = Completed, default: 1)
- `due_date` (optional): Due date in Y-m-d H:i:s format
- `assigned_to` (optional): Array of user IDs to assign to the task

#### Get Task

```
GET /tasks/{id}
```

Response:
```json
{
  "data": {
    "id": 1,
    "title": "Complete project documentation",
    "description": "Write comprehensive documentation for the project",
    "status": 1,
    "due_date": "2023-04-15 12:00:00",
    "created_at": "2023-03-27T12:34:56.000000Z",
    "updated_at": "2023-03-27T12:34:56.000000Z",
    "assigned_users": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      }
    ],
    "comments": [
      {
        "id": 1,
        "content": "I've started working on this",
        "user": {
          "id": 1,
          "name": "John Doe"
        },
        "created_at": "2023-03-28T09:45:12.000000Z",
        "replies": []
      }
    ]
  }
}
```

#### Update Task

```
PUT /tasks/{id}
```

Request Body:
```json
{
  "title": "Updated Task Title",
  "description": "Updated task description",
  "status": 2,
  "due_date": "2023-06-15 10:00:00",
  "assigned_to": [1, 3]
}
```

All fields are optional. Only include the fields you want to update.

#### Delete Task

```
DELETE /tasks/{id}
```

Response:
```json
{
  "status": "success",
  "message": "Task deleted successfully"
}
```

### Comments

#### List Comments

```
GET /comments?task_id=1
```

or for replies to a comment:

```
GET /comments?comment_id=1
```

Must include either `task_id` or `comment_id` parameter.

Response:
```json
{
  "data": [
    {
      "id": 1,
      "content": "I've started working on this",
      "user": {
        "id": 1,
        "name": "John Doe"
      },
      "created_at": "2023-03-28T09:45:12.000000Z",
      "replies_count": 2
    }
  ],
  "meta": { ... },
  "links": { ... }
}
```

#### Create Comment

```
POST /comments
```

Request Body (for a task comment):
```json
{
  "content": "This is a comment on the task",
  "task_id": 1
}
```

Request Body (for a reply to another comment):
```json
{
  "content": "This is a reply to a comment",
  "comment_id": 1
}
```

You must include either `task_id` or `comment_id`, but not both.

#### Update Comment

```
PUT /comments/{id}
```

Request Body:
```json
{
  "content": "Updated comment content"
}
```

#### Delete Comment

```
DELETE /comments/{id}
```

Response:
```json
{
  "status": "success",
  "message": "Comment deleted successfully"
}
```

## Rate Limiting

The API implements rate limiting to prevent abuse. By default, clients are limited to 60 requests per minute. When the limit is exceeded, the API returns a `429 Too Many Requests` response with headers indicating the rate limit status:

- `X-RateLimit-Limit`: Maximum number of requests allowed per time window
- `X-RateLimit-Remaining`: Number of requests remaining in the current time window
- `Retry-After`: Number of seconds to wait before making another request

## Caching

The API implements caching for certain endpoints to improve performance. Cached responses include these headers:

- `Cache-Control`: Caching directives
- `ETag`: Entity tag for content validation

To bypass the cache, include the `Cache-Control: no-cache` header in your request.

## Versioning

The API is versioned through the URL path (`/api/v1/`). This ensures backward compatibility as the API evolves. Future versions will be accessible at `/api/v2/`, etc.

## Changes and Deprecation Policy

- Backward-incompatible changes will only be introduced in new API versions
- Deprecated endpoints will continue to function for at least 6 months before removal
- Deprecation notices will be included in the response headers:
  ```
  Deprecation: true
  Sunset: Sat, 31 Dec 2023 23:59:59 GMT
  ```

## Support

For API support and questions, contact:

- Email: api-support@taskmanagement.com
- Documentation: https://taskmanagement.com/docs 
