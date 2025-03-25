<template>
    <div id="opac-user-views" class="toptabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a
                    href="#opac-user-checkouts_panel"
                    class="nav-link active"
                    id="opac-user-checkouts-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#opac-user-checkouts_panel"
                    data-tabname="opac-user-checkouts"
                    aria-controls="opac-user-checkouts_panel"
                    role="tab"
                    aria-selected="true"
                >
                    <span>Checked out (0)</span>
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a
                    href="#opac-user-article-requests_panel"
                    class="nav-link"
                    id="opac-user-article-requests-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#opac-user-article-requests_panel"
                    data-tabname="opac-user-article-requests"
                    aria-controls="opac-user-article-requests_panel"
                    role="tab"
                    aria-selected="false"
                    tabindex="-1"
                >
                    <span>Article requests (0)</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div
                role="tabpanel"
                class="tab-pane active show"
                id="opac-user-checkouts_panel"
                aria-labelledby="opac-user-checkouts-tab"
            >
                <table class="table table-bordered table-striped">
                    <caption class="sr-only">
                        Checked out
                    </caption>
                    <tbody>
                        <tr>
                            <td>You have nothing checked out</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                role="tabpanel"
                class="tab-pane"
                id="opac-user-article-requests_panel"
                aria-labelledby="opac-user-article-requests-tab"
            >
                <table class="table table-bordered table-striped">
                    <caption class="sr-only">
                        Article requests
                    </caption>
                    <tbody>
                        <tr>
                            <td>You have no article requests currently.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="opac-user-hoopla">
            <div id="content-hoopla">
                <p>
                    Search the catalog to find and or checkout Hoopla items.<br />Click
                    the links to visit the Hoopla site and login to download
                    items or get the apps
                </p>
            </div>
        </div>
    </div>
</template>
<style>
.nav-tabs .nav-link.active {
    background-color: #fff;
    border: 1px solid #d8d8d8;
    border-bottom-color: rgba(0, 0, 0, 0);
    color: #000;
    cursor: default;
}
.nav-tabs .nav-link {
    background-color: #f0f3f3;
    border: 1px solid #dddddd;
    color: #0074ad;
    font-weight: bold;
    margin-right: 0.4em;
    text-decoration: none !important;
}
.table.table-bordered {
    border-top: 0px solid #dee2e6;
}
</style>
