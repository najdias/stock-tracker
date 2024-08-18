# Stock-Tracker
## Version: 1.0.0

### /login_check

#### POST
##### Summary:

Login Check

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful response |
| 401 | Unauthorized response |

### /register

#### POST
##### Summary:

Register

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful response |

### /stock

#### GET
##### Summary:

Stock

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| q | query |  | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful response |

##### Security

| Security Schema | Scopes |
| --- | --- |
| bearerAuth | |

### /stock/history

#### GET
##### Summary:

History

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful response |

##### Security

| Security Schema | Scopes |
| --- | --- |
| bearerAuth | |
