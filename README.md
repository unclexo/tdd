## Test Your Laravel Application

A [Laravel](https://laravel.com/) app that has a number of implemented test cases. Do you love to TDD your Laravel 
application or don't know how to unit test your Laravel application? This repo will help you there. Get started with 
Test Driven Development.

Happy TDD

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

### Where to start

Where do I start? Don't worry! It happens. When you're not sure where to start, try to figure out what module or class 
or function you're going to deal with. If you get one, break it down into a set of tasks. Then pick a task and go with 
that.

If it does not work try to fake a list of tasks. Even if this one does not work too, take a walk and repeat.

### Testing Form Submission
##### How to
- [Test a simple form submission](https://github.com/unclexo/test-laravel-app/commit/b97d903e491156a4cdb0cefd379639310ff6a22f)
- [Test creating a blog post](https://github.com/unclexo/test-laravel-app/commit/f4f61092115518780997cc96ba959486479a19ad#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR12)
- [Test displaying a blog post](https://github.com/unclexo/test-laravel-app/commit/cc242030ff7499cf2c6ae7d0ca78f292b206da86#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR22)
- [Test redirection after creating a post](https://github.com/unclexo/test-laravel-app/commit/01ea64150a94e8a0c193febaa02c225b6020d7c5#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR22)
- [Test validating a simple form](https://github.com/unclexo/test-laravel-app/commit/ac801e2ddb57b74d069e5ae0ad9bad558ebc4277#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR48)
- [Test updating a blog post](https://github.com/unclexo/test-laravel-app/commit/b142dfc89ed5dcdd2cc14f7b38a0f2401808050b#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR71)
- [Test deleting a blog post](https://github.com/unclexo/test-laravel-app/commit/4e8286c0bc265a3969c8a193491dc8fcc51a281b#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR86)
- [Test unauthenticated users cannot manage posts](https://github.com/unclexo/test-laravel-app/commit/b938a750deb49e954b36b30590a3e07f04dde9f9#diff-01c9b5c8d18a1e363a9856e23a7085909ca74d37cacdffd63d7c2562c7ad5a0cR98)

### Testing File Upload
##### How to
- [Test a simple image upload](https://github.com/unclexo/test-laravel-app/commit/e08dbd08777ffda7969caa57936e36f36f1f9849)
- [Test renaming an uploaded image](https://github.com/unclexo/test-laravel-app/commit/adde09542d199625baa10ce3879e4b904efb0fda#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR27)
- [Test validating an uploaded image](https://github.com/unclexo/test-laravel-app/commit/05286dd6301039fa5e3a5bebd25154d6454b2868#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR46)
- [Test uploading multiple files with validation](https://github.com/unclexo/test-laravel-app/commit/a2c9d24f5e25d9c7f7eafb2c4e43a163802387b8#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR67)
- [Test resizing uploaded image](https://github.com/unclexo/test-laravel-app/commit/fe489a5ed36f5b906ed46f65dc84307ceec96633#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR87)
- [Test downloading a private file](https://github.com/unclexo/test-laravel-app/commit/15eb9df78d863dca6a4ceeb0d98abd94eee4a4dd#diff-50c5279f0b565ef1db22b63db589247302e4d1251fe51cb60401ab497939b9ceR105)
