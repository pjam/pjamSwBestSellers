<style>
    .pjamBestSellersList .list .articleLine {
        padding: 10px 5px;
        border-bottom: 1px dotted #dadae5;
    }
    .pjamBestSellersList .list .articleLine:hover {
        background-color: rgba(243, 89, 36, 0.08);
    }
    .pjamBestSellersList .list .articleLine > div {
        display: inline-block;
        vertical-align: middle;
    }
    .pjamBestSellersList .list .articleLine .articleName
    {
      width: 79%;
      font-weight: bold;
      padding: 0 3px;
    }
    .pjamBestSellersList .list .articleLine .articleImage
    {
      width: 19%;
    }
    .pjamBestSellersList .list .articleLine img
    {
        max-height: 50px;
        max-width: 100%;
        margin: 0 auto;
    }
</style>

<div class="pjamBestSellersList sidebar--navigation">
  <div class="panel--title is--underline">Best Sellers</div>
  <div class="list">
    {foreach key=aid item=art from=$articles}
    <div class="articleLine">
      <div class="articleImage">
        <img src="{$art.image}" title="{$art.name}" />
      </div>
      <div class="articleName">{$art.name}</div>
    </div>
    {foreachelse}
      There are no sales registered for the given period
    {/foreach}
  </div>
</div>
