<section class="wrapper">
    <div class="inner">
        <%-- Breadcrumbs --%>
        <% include BreadCrumbs %>
        <hr>

        <% if $Article %>
            <h1 id="article-$ID">$Article.Title.RAW</h1>
            <p>$Article.Content</p>
            <% if $NewsImages %>
                <p><strong><%_t('WWN\News\NewsImage.PLURALNAME', 'Images')%></strong></p>
                <div id="$ID">
                    <% loop $NewsImages %>
                        <a href="$Image.URL" alt="$Title" title="$Title">
                            <img src="$Image.URL"
                                 alt="$Title"
                                 title="$Title">
                        </a>
                    <% end_loop %>
                </div>
            <% end_if %>
        <% end_if %>
    </div>
</section>
