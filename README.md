## Test Your Laravel Application

The Laravel app has a number of implemented feature test cases that may help you understand how to test which 
feature of your Laravel app. If you love to TDD your Laravel application or don't know how then this repo will help 
you there. Get started with Test Driven Development and Happy TDD!

Don't hesitate to ask me on [Twitter](https://twitter.com/unclexo) 
if you don't understand any of these tests.

**Note** that some tests are not refactored here.

### TDD in few words

In Test-Driven Development, you've two rules:
- Write new code only if you first have a failing automated test.
- Eliminate duplication.

The two rules imply an order to the tasks of programming:

1. **Red** - write a little test that doesn’t work, perhaps doesn’t even compile at first
2. **Green** - make the test work quickly, committing whatever sins necessary in the
process
3. **Refactor** - eliminate all the duplication created in just getting the test to work

**Red** / **Green** / **Refactor** - is called the TDD mantra.

This is from [Kent Beck's](https://en.wikipedia.org/wiki/Kent_Beck) [TDD by example](https://www.amazon.com/Test-Driven-Development-Kent-Beck/dp/0321146530).

### Installation

Follow these steps to set up the application.

#### 1. Build Docker Image

```bash
docker compose build --build-arg USER=$(whoami) --build-arg UID=$(id -u) --build-arg GID=$(id -g)
```

#### 2. Start Containers

```bash
docker compose up -d
```

#### 3. Verify Running Containers

```bash
docker container list
```

#### 4. Install PHP Dependencies

```bash
docker exec -it tdd-php composer install
```

#### 5. Set Application Key

Copy the example environment file:

```bash
cp .env.example .env
```

Then generate the application key:

```bash
docker exec -it tdd-php php artisan key:generate
```

#### 6. Install and Build Frontend

```bash
yarn install && yarn build
```

#### 7. Run Tests

```bash
docker exec -it tdd-php php artisan test
```

### Where to start

Where do I start? Don't worry! When you're not sure where to start, try to figure out what module or class 
or function you're going to deal with. If you get one, break it down into a set of tasks. Then pick a task and go with 
that.

If it does not work try to fake a list of tasks.

### Testing Form Submission

##### How to

- [Test a simple form submission](https://github.com/unclexo/test-laravel-app/commit/b97d903e491156a4cdb0cefd379639310ff6a22f)
- [Test creating a blog post](https://github.com/unclexo/test-laravel-app/commit/f4f61092115518780997cc96ba959486479a19ad#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR15)
- [Test displaying a blog post](https://github.com/unclexo/test-laravel-app/commit/cc242030ff7499cf2c6ae7d0ca78f292b206da86#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR25)
- [Test redirection after creating a post](https://github.com/unclexo/test-laravel-app/commit/01ea64150a94e8a0c193febaa02c225b6020d7c5#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR25)
- [Test validating a simple form](https://github.com/unclexo/test-laravel-app/commit/ac801e2ddb57b74d069e5ae0ad9bad558ebc4277#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR51)
- [Test updating a blog post](https://github.com/unclexo/test-laravel-app/commit/b142dfc89ed5dcdd2cc14f7b38a0f2401808050b#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR74)
- [Test deleting a blog post](https://github.com/unclexo/test-laravel-app/commit/4e8286c0bc265a3969c8a193491dc8fcc51a281b#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR89)
- [Test unauthenticated users cannot manage posts](https://github.com/unclexo/test-laravel-app/commit/b938a750deb49e954b36b30590a3e07f04dde9f9#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR101)

### Testing File Upload

##### How to

- [Test a simple image upload](https://github.com/unclexo/test-laravel-app/commit/e08dbd08777ffda7969caa57936e36f36f1f9849)
- [Test renaming an uploaded image](https://github.com/unclexo/test-laravel-app/commit/adde09542d199625baa10ce3879e4b904efb0fda#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR30)
- [Test validating an uploaded image](https://github.com/unclexo/test-laravel-app/commit/05286dd6301039fa5e3a5bebd25154d6454b2868#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR49)
- [Test uploading multiple files with validation](https://github.com/unclexo/test-laravel-app/commit/a2c9d24f5e25d9c7f7eafb2c4e43a163802387b8#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR70)
- [Test resizing uploaded image](https://github.com/unclexo/test-laravel-app/commit/fe489a5ed36f5b906ed46f65dc84307ceec96633#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR90)
- [Test downloading a private file](https://github.com/unclexo/test-laravel-app/commit/15eb9df78d863dca6a4ceeb0d98abd94eee4a4dd#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR108)


### Testing Sending Email

#### What to test?

What to test while sending an email? Well, you don't need to test how to send emails under the hood. Because that's the 
job of Laravel email API, not yours. So, test Laravel's email API can be instructed to send emails.

##### How to

- [Test mailables are available](https://github.com/unclexo/test-laravel-app/commit/fecdf9d594ee690ffd84c07454043cd5e2a440eb#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR15)
- [Test previewing email template](https://github.com/unclexo/test-laravel-app/commit/13e5edd7064353cdb8236f9373b184838efb4f93#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR33)
- [Test mailable has valid content](https://github.com/unclexo/test-laravel-app/commit/9693f2c6e2922838b58a4e524342d47008e626f9#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR44)
- [Test setting content to mailable at runtime](https://github.com/unclexo/test-laravel-app/commit/7e4852682df3c7246958e9b0a29a12729dc12106#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR76)
- [Test mailable can have attachments](https://github.com/unclexo/test-laravel-app/commit/e80c698e85fa3f957f4d41b1d52791eaf96edef8#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR114)
- [Test mailable can have attachments at runtime](https://github.com/unclexo/test-laravel-app/commit/4a3873bc769981450fbd91367b9221f0ef4201fe#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR127)
- [Test sending email](https://github.com/unclexo/test-laravel-app/commit/e7bee040b831c8e847586b1aecd2a97c735d934a#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR142)
- [Test sending email via queue](https://github.com/unclexo/test-laravel-app/commit/95447528f3aea5a3fea2a6313c88e725cf5ec311#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR159)
- [Test sending email to bulk users](https://github.com/unclexo/test-laravel-app/commit/05017a81ada5f2a596f206b6d02570e8a7539982#diff-02065eb58905bf99b4529ca7a41cd828b4788e3a96377e1d687351e6a7b0715bR176)


### Testing Events and Listeners

#### What to test?

Test the code that triggers an event to check it was dispatched. You don't need to test the execution of 
a listener. That's Laravel's event API's job. You may unit-test what the listener's `handle()` method does and 
mock the method's call.

##### How to

- [Test listeners can listen to given events](https://github.com/unclexo/test-laravel-app/commit/a889a512c66f2c5bc43f601577a193f97568249e#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R19)
- [Test triggering an event after creating orders](https://github.com/unclexo/test-laravel-app/commit/0c51d73e2ba892b5da5afa7c95556715777694ec#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R43)
- [Test triggering an event after updating orders](https://github.com/unclexo/test-laravel-app/commit/f684622b6cc100c0620b29bf3972ee52664336ee#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R62)
- [Test triggering an event after deleting orders](https://github.com/unclexo/test-laravel-app/commit/f25df8761d8ad225a25b7223e1fe6c39a3f0e57b#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R79)
- [Test listening to builtin events](https://github.com/unclexo/test-laravel-app/commit/94fac2aee6aa45ecc7d718a673ccaec9d5bd340a#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R97)
- [Test subscribing to builtin event - Login](https://github.com/unclexo/test-laravel-app/commit/27ff63f7286539a09dcc2ae4e37ba236aa2b121c#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R107)
- [Test subscribing to builtin event - Logout](https://github.com/unclexo/test-laravel-app/commit/606217e75e5fff12d17cca292377e2f2ed78f652#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R127)
- [Test instructing an event listener to be queued](https://github.com/unclexo/test-laravel-app/commit/ac668406aaf5a7db094eb2da0dfd4f90e6b29a37#diff-0487aa68cfc42ba8667af5853cb997ada682c50774fb9e634370102518e29b92R145)
- [Test handle method of an event listener](https://github.com/unclexo/test-laravel-app/commit/5b9c22097cf984da7750263488fb409ed3018d40#diff-20de2c0e1bc98b2ce611be196d25576c92534582081c2958a7f0945846e11fcdR17)


### Testing Notifications

#### What to test?

Test Laravel notification API can be instructed to send notifications. Because sending notifications is unrelated to 
the code you are actually testing.

##### How to

- [Test notifying users after shipping orders](https://github.com/unclexo/test-laravel-app/commit/5c9418d0d01bc823315e567d442096414313b199#diff-669bf523012cdae2c3873beab01ab7ed24936518e43c2fcbff2406dbe551b6ddR20)
- [Test notifying user while requesting reset password link](https://github.com/unclexo/test-laravel-app/commit/7e26369343e5517daf8fd9cff3910f61e29af50e#diff-669bf523012cdae2c3873beab01ab7ed24936518e43c2fcbff2406dbe551b6ddR37)
- [Test reset password screen can be seen with token](https://github.com/unclexo/test-laravel-app/commit/2ce0701ffdb404d02fb46ae084a21dfd1709de7d#diff-669bf523012cdae2c3873beab01ab7ed24936518e43c2fcbff2406dbe551b6ddR51)
- [Test password can be reset with valid token](https://github.com/unclexo/test-laravel-app/commit/6aab4fbae90f91104b857bcb064537ef64c24346#diff-669bf523012cdae2c3873beab01ab7ed24936518e43c2fcbff2406dbe551b6ddR69)
- [Test a notification can be instructed to be queued](https://github.com/unclexo/test-laravel-app/commit/9c9ce2636be6c9f786fb3f8d1ad590ac7b9b3cac#diff-669bf523012cdae2c3873beab01ab7ed24936518e43c2fcbff2406dbe551b6ddR94)


### Testing Jobs / Queue 

#### Test-Driven Development with a real world example 

This section is for how to upload, resize, and store image info into a database but via a **queued** job and 
**applying TDD**. What to test or where to start is outlined below. If you follow the steps given below, you'll have 
a good grasp of how to proceed with TDD while developing a new feature or making a change.      

**Note**: Make sure you've installed `predis/predis` and `intervention/image`, configured redis, and run migrations.

**Tip**: Go through each file involved in a particular test.

Step 1 - [Test a job is dispatchable](https://github.com/unclexo/test-laravel-app/commit/6de30acc8a4c74e7e6d193d7e2d820d28d8773e8#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R14)

Step 2 - [Test a job can be queued](https://github.com/unclexo/test-laravel-app/commit/41912438468ddcdb74025256d2847299db6fcc74#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R32)

Step 3 - [Test a queued job can upload and resize an image](https://github.com/unclexo/test-laravel-app/commit/b49cd299092364746a367e8e0a41f9c7ffb5370a#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R49)

Step 4 - [Test handle method returns false on upload fail](https://github.com/unclexo/test-laravel-app/commit/5ff4b35b936eaa9c248807c8c0911f23666fb69c#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R64)

Step 5 - [Test handle method returns false on invalid mime type](https://github.com/unclexo/test-laravel-app/commit/2ff6ae5d38cf402114fc61857001c07d589a8414#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R82)

Step 6 - [Test handle method returns false on invalid image content](https://github.com/unclexo/test-laravel-app/commit/3a99822b66d4723d47fa1ba8056b336670ec3504#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R92)

Step 7 - [Test handle method returns false on invalid resolutions](https://github.com/unclexo/test-laravel-app/commit/ace2fc689543a50c0eb209d863b35640cfb6382c#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R107)

Step 8 - [Test handle method deletes original image after resizing it](https://github.com/unclexo/test-laravel-app/commit/764cd0d6426482ddcde8a7591938ac8f1f5d5e86#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R130)

Step 9 - [Test refactoring and storing image info into database](https://github.com/unclexo/test-laravel-app/commit/f831762763ababf302d9540a0bf461f912b36983#diff-23b75d71e87d07f479550169f3f37eb57ef3ab8c71afbd9a89c692df21a6f7e8R153)

Hooray! You've done test-driven development!

**Note** that I've left some refactorings and tests for you. The calling of methods of this `Intervention\Image\Facades\Image` 
class is not stubbed here, for example. 

One more thing, most tests about the `Job` class should go to the `Unit` section. But I've put them here for you so that you can 
see them all in a single place.
