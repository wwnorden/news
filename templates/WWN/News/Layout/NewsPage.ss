<section class="wrapper">
    <div class="inner">
        <%-- Breadcrumbs --%>
        <% include BreadCrumbs %>
        <hr>

        <h1>$Headline.RAW</h1>
        <br>
        <% if $Lead %><p>$Lead.RAW</p><% end_if %>
        <% if $Content %>
            $Content
        <% end_if %>

        <% if $PaginatedNews %>
            <% loop $PaginatedNews %>
                <span>$Date.Format('dd.MM.y')</span>
                <h3>$Title.RAW</h3>
                <p>$Content.LimitWordCount(25)</p>
                <a href="$Top.Link$URLSegment/"><% _t('WWN\News\NewsArticle.ReadMore', 'Read more') %></a>

                <% if $Links %>
                    <p><strong>Links</strong></p>
                    <ul>
                        <% loop $Links %>
                            <li><a href="$URL" title="$Source" target="_blank" class="button">$Title</a></li>
                        <% end_loop %>
                    </ul>
                <% end_if %>
                <% if $NewsImages %>
                    <p><strong><% _t('WWN\News\NewsImage.images', 'images') %></strong></p>
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
                <br>
                <% if not $last %>
                    <hr>
                <% end_if %>
            <% end_loop %>

            <hr>

            <% if $PaginatedNews.MoreThanOnePage %>
                <% if $PaginatedNews.NotFirstPage %>
                    <a class="prev button"
                       href="$PaginatedNews.PrevLink"><% _t('WWN\News\NewsArticle.prev','Previous')%></a>
                <% end_if %>
                <% loop $PaginatedNews.PaginationSummary %>
                    <% if $CurrentBool %>
                        <p class="button disabled">$PageNum</p>
                    <% else %>
                        <% if $Link %>
                            <a href="$Link" class="button">$PageNum</a>
                        <% else %>
                            ...
                        <% end_if %>
                    <% end_if %>
                <% end_loop %>
                <% if $PaginatedNews.NotLastPage %>
                    <a class="next button"
                       href="$PaginatedNews.NextLink"><% _t('WWN\News\NewsArticle.next','Next')%></a>
                <% end_if %>
            <% end_if %>
        <% end_if %>
    </div>
</section>




