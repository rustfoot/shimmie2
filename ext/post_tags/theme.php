<?php

declare(strict_types=1);

namespace Shimmie2;

use MicroHTML\HTMLElement;

use function MicroHTML\{joinHTML, A, TEXTAREA};

class PostTagsTheme extends Themelet
{
    public function display_mass_editor(): void
    {
        global $page;
        $html = "
		" . make_form(make_link("tag_edit/replace")) . "
			<table class='form'>
				<tr><th>Search</th><td><input type='text' name='search' class='autocomplete_tags'></tr>
				<tr><th>Replace</th><td><input type='text' name='replace' class='autocomplete_tags'></td></tr>
				<tr><td colspan='2'><input type='submit' value='Replace'></td></tr>
			</table>
		</form>
		";
        $page->add_block(new Block("Mass Tag Edit", $html));
    }

    public function get_tag_editor_html(Image $image): HTMLElement
    {
        global $user;

        $tag_links = [];
        foreach ($image->get_tag_array() as $tag) {
            $tag_links[] = A([
                "href" => search_link([$tag]),
                "class" => "tag",
                "title" => "View all posts tagged $tag"
            ], $tag);
        }

        return SHM_POST_INFO(
            "Tags",
            joinHTML(", ", $tag_links),
            $user->can(Permissions::EDIT_IMAGE_TAG) ? TEXTAREA([
                "class" => "autocomplete_tags",
                "type" => "text",
                "name" => "tag_edit__tags",
                "id" => "tag_editor",
                "spellcheck" => "off",
            ], $image->get_tag_list()) : null,
            link: Extension::is_enabled(TagHistoryInfo::KEY) ?
                make_link("tag_history/{$image->id}") :
                null,
        );
    }
}
