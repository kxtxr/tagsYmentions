# tagsYmentions
Twitter-like hashtags and mentions

## Manual Installation without Composer
You can use this library pretty much anywhere but in this doc, I will use it with codeigniter
1. Copy folder **system** to your codeigniter projects.
2. Add this code to your codeigniter index.php before codeigniter core loaded (before this text "* LOAD THE BOOTSTRAP FILE") :
    ```php
		/*
		 * --------------------------------------------------------------------
		 *
		 * And away we go...
		 *
		 */

		require_once BASEPATH . 'tagsYmentions/autoloader.php';
    
    ...
    
    $str = "#lol...This #message was meant for @16kilobyte"

		$tagsYmentions = new TagsYMentions\TagsYMentions($str);
    $tagsYmentions->taggedYMentioned(); //<a href="/tags/lol">#lol</a>...This <a href="/tags/message">#message</a> was meant for <a href="/users/16kilobyte">@16kilobyte</a>
    $tagsYmentions->getHashTags(); // ['#lol', '#message']
    $tagsYmentions->getMentions(); // ['@16kilobyte']
    $tagsYmentions->getHashTagsYMentions(); //['tags' => ['#lol', '#message'], 'mentions' => ['@16kilobyte']]
    $tagsYmentions->getHashTagsYMentions(); //['#lol', '#message', '@16kilobyte']

    $tagsYmentions->setString("The new #string I want to #parse"); // Changes the game.
    ```
## Usage
You can specify the URL to use for both tags and mentions. For example;

    ```php
    $tagsYmentions = new TagsYMentions\TagsYMentions($str, ['tags' => ['url' => 'https://github/topics/', title => TagsYMentions\TagsYMentions::USE_NAME], 'mentions' => ['url' => 'https://github.com', title => TagsYMentions\TagsYMentions::USE_NAME]]);
    $tagsYmentions->taggedYMentioned(); //<a href="https://github.com/topics/lol">#lol</a>...This <a href="https://github.com/topics/message">#message</a> was meant for <a href="https://github.com/16kilobyte">@16kilobyte</a>
    ```

## Meta

Kator Bryan `16kb` James – [@JamesKator](https://twitter.com/JamesKator) – kator95@gmail.com

Distributed under the MIT license. See ``LICENSE`` for more information.

[https://github.com/16kilobyte/tagsYmentions](https://github.com/16kilobyte/tagsYmentions)

## Contributing

1. Fork it (<https://github.com/16kilobyte/tagsYmentions/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request
