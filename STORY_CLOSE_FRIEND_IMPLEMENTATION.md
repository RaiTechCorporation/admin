# Story Close Friend Feature - Flutter Implementation Guide

This guide details the API endpoints and data structures required to implement the "Close Friends" story feature in the Flutter application.

## 1. Manage Close Friends

### Toggle Close Friend
Add or remove a user from the current user's close friends list.

- **Endpoint**: `user/toggleCloseFriend`
- **Method**: `POST`
- **Headers**: `authtoken: <USER_TOKEN>`
- **Body**:
```json
{
  "user_id": "TARGET_USER_ID"
}
```
- **Response**:
```json
{
  "status": true,
  "message": "User added to close friends" // or "User removed from close friends"
}
```

### Fetch Close Friends
Retrieve the list of users currently in the close friends list.

- **Endpoint**: `user/fetchCloseFriends`
- **Method**: `POST`
- **Headers**: `authtoken: <USER_TOKEN>`
- **Response**:
```json
{
  "status": true,
  "message": "Close friends fetched successfully",
  "data": [
    {
      "id": 1,
      "friend": {
        "id": 10,
        "username": "johndoe",
        "fullname": "John Doe",
        "profile_photo": "https://...",
        "is_verify": 1
      }
    }
  ]
}
```

## 2. Story Creation

### Create Story (Updated)
When creating a story, the `is_close_friend` flag can now be passed to restrict visibility.

- **Endpoint**: `post/createStory`
- **Method**: `POST` (Multipart)
- **Headers**: `authtoken: <USER_TOKEN>`
- **Parameters**:
    - `type`: (required) 0 for image, 1 for video
    - `content`: (required) file
    - `duration`: (optional)
    - `sound_id`: (optional)
    - `thumbnail`: (optional)
    - `mentioned_user_ids`: (optional) comma-separated string of IDs
    - `is_close_friend`: (optional) `1` for close friends only, `0` for everyone (default)

## 3. Story Discovery

### Fetch Stories (Updated)
The `post/fetchStory` endpoint now automatically filters stories based on relationships.
- Stories with `is_close_friend = 1` will **only** be returned if the viewing user is in the author's close friends list.
- Stories with `is_close_friend = 0` are returned for all followers.

## 4. User Details

### Fetch User Details (Updated)
The `user/fetchUserDetails` endpoint now includes a field indicating if the target user is in your close friends list.

- **Endpoint**: `user/fetchUserDetails`
- **Response Data Snippet**:
```json
{
  "status": true,
  "data": {
    "id": 10,
    "username": "johndoe",
    "is_close_friend": true, // New field
    "is_following": true,
    ...
  }
}
```

## 5. Flutter Implementation Tips

### Close Friend Badge logic
When displaying stories in Flutter, you can check the `is_close_friend` field in the story object to show a "Green Circle" or "Close Friends" badge.

```dart
if (story.isCloseFriend == 1) {
  // Show green circle/badge
} else {
  // Show standard gradient circle
}
```

### Mention Notifications
If a user is mentioned in a "Close Friend" story, they will receive a specific notification type (`notify_close_friend_mention_story` = 10) which can be used to display a specialized notification message in the app.
