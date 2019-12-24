<section class="wrapper">
    <div class="inner">
        <%-- Breadcrumbs --%>
        <% include Breadcrumbs %>
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
                <p>$Content</p>

                <% if $Links %>
                    <p><strong>Links</strong></p>
                    <ul class="actions">
                    <% loop $Links %>
                        <li class="margin-bottom"><a href="$URL" title="$Source" target="_blank" class="button alt small">$Title</a></li>
                    <% end_loop %>
                    </ul>
                <% end_if %>
                <% if $NewsImages %>
                    <p><strong>Bilder</strong></p>
                    <div id="$ID">
                        <% loop $NewsImages %>
                            <a href="$Image.URL" alt="$Title" title="$Title">
                                <img src="$Image.URL"
                                     class="img-rounded image-list"
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
                    <a class="prev button alt small" href="$PaginatedNews.PrevLink">Vorherige</a>
                <% end_if %>
                <% loop $PaginatedNews.PaginationSummary %>
                    <% if $CurrentBool %>
                        <p class="button alt disabled">$PageNum</p>
                    <% else %>
                        <% if $Link %>
                            <a href="$Link" class="button alt small">$PageNum</a>
                        <% else %>
                            ...
                        <% end_if %>
                    <% end_if %>
                <% end_loop %>
                <% if $PaginatedNews.NotLastPage %>
                    <a class="next button alt small" href="$PaginatedNews.NextLink">Nächste</a>
                <% end_if %>
            <% end_if %>
        <% end_if %>
    </div>
</section>




