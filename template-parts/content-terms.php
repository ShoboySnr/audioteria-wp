	<div class="wrapper terms-and-conditions ">
	    <ul class="book-nav">
	        <li>
	            <a title="Home" href="<?= home_url('/') ?>"><?=__('Home', 'audioteria-wp')?> </a>
	        </li>
					<li class="separator separator-home"> | </li>
	        <li class="active">
	            <a title="Terms and Conditions"
	                href="<?= get_permalink(get_the_ID());?>"><?=__('Terms and Conditions', 'audioteria-wp')?></a>
	        </li>
	    </ul>
	    <article>
	        <h1><?= the_title()?></h1>
	        <article>
	            <p class="note">
	                <?=__('PLEASE NOTE THAT YOU, OR YOUR PARENTS ON YOUR BEHALF, ARE AGREEING TO THESE TERMS AND
	                CONDITIONS
	                WHEN
	                YOU USE THIS SITE. IF YOU DO NOT AGREE WITH THESE TERMS, YOU SHOULD NOT USE THE SITE. SUMMARY OF THE
	                STUFF BELOW: The site is for your own and not commercial', 'audioteria-wp')?></p>
	            <div class="body">
	                <?php the_content();?>
	            </div>

	        </article>
	    </article>
	</div>