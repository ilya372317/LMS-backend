# Game describe:

> Idea of this game, is users create quest for each other, establish
> reward and improve characteristics their character.

## 1. Game process.

### Characters: 

- Quest giver - create a quest and suggest execute them to Quest executor.
- Quest executor - get quest from Quest giver and and execute it.

### Quest giver: 
- Quest giver open list of Quest executors and read them description and characteristics
- Select one of them. 
- Fill quest information (title, description, categories, reward, photos, files).
- Quest basic on selected category generate skill improvement value for quest complete.
- Then Quest executor accept or cancel given quest, get notification about that.
- If quest executor accept quest, can send message and files to executor, for control or help quest execution.
- After quest executor press 'quest complete' check it.
- If quest complete approve this and send reward on real world.
- If quest not complete, send to quest executor information about what wrong and quest continues.
- If quest executor report for quest giver not give reward to him, moderator check it, and can Ban executor for this.
- If quest executor approve received reward, quest complete and executor get + carma point.

### Quest executor
- Wake up and check privacy cabinet, where see new quest.
- Or get notification about new quest from e-mail.
- Login into game and view information about quest.

### Entities 
- User - base entity for all users on the game. Used to authenticate, participate in quest and so on.
- Quest - central entity of all game. Have relations with quest giver and Quest executor based on user_id and all information about reward, skill improvement and other. Have a several status.
- Classes - character classes (geek, worker, blogger)
- account_notification.
- user_characteristics
- characteristic
- quest_files
- account_files
- chat_files
- chat
- chat_messages

### User entity properties
- id
- name
- login 
- password
- class (relation on category entity)
- status (active, blocked)
- carma_point
- characteristic_set (relation on user_characteristic entity)
- email
- email_verified_at
- created_at
- updated_at

### Quest entity properties
- id
- title
- description
- relations images
- relations files
- chat (related to chat entity);
- status
- created_at
- updated_at

### Classes entity properties
- id
- title
- description
- relations images
- created_at
- updated_at

### AccountNotification entity properties
- id
- user_id
- message
- is_read
- created_at
- updated_at

### Characteristics
- id 
- name
- max_value
- min_value
- image
- created_at
- updated_at

### UserCharacteristics entity properties
- id 
- user_id
- characteristics_id
- value
- created_at
- updated_at

### QuestFiles entity properties
- id
- name
- path
- public_url
- size
- quest_id
- type (image/other)
- created_at
- updated_at

### AccountFiles entity properties 
- id 
- name
- path
- public_url
- size
- user_id
- type (image/other)
- created_at
- updated_at

### ChatFiles entity properties
- id
- name
- path
- public_url
- size
- type (image/other)
- chat_id
- created_at
- updated_at

### Chat entity properties
- id
- users_ids[]
- created_at
- updated_at

### ChatMessages entity properties
- id
- user_id
- chat_id
- text
- chat_file
- created_at 
- updated_at