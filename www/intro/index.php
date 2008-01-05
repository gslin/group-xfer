<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Group.NCTU.edu.tw</title>
  <link rel="stylesheet" type="text/css" href="/group.css" />
</head>

<body>

<? include('../template/toolbar.inc.php'); ?>

<h1>Group.NCTU.edu.tw 系統說明</h1>

<hr />

<ul>
  <li>
    <h2>創設目的</h2>

    <p>在台灣的網路社會中，BBS 的使用相當盛行，看板之間的轉信使得資訊流通的速度加快。透過 <abbr title="由 skhuang@csie.nctu.edu.tw 所發展">innbbsd</abbr> 做少數站台 (小於四站) 的轉信是相當方便的，但超過四站之後，僅使用 <abbr title="由 skhuang@csie.nctu.edu.tw 所發展">innbbsd</abbr> 互轉會使得設定變得相當冗長。</p>
    <div class="center"><img alt="peering1.png" src="peering1.png" /><br />（圖一）互轉架構</div>

    <p>一般來說，大量站台的轉信都會使用 News Server 進行星狀流通，Group.NCTU.edu.tw 即是為此而成立。</p>
    <div class="center"><img alt="peering2.png" src="peering2.png" /><br />（圖二）星狀架構</div>
  </li>

  <li>
    <h2>系統架構</h2>

    <p>以往透過 News Server 轉信會使用同一個 Newsgroup Name，如台灣 386BSD 板的 Newsgroup Name 為 <a href="news:tw.bbs.comp.386bsd"><code>tw.bbs.comp.386bsd</code></a>。</p>
    <p>在本系統中，對於同一個看板之間的轉信，我們揚棄此種觀念：也就是說，對於同一個板的轉信，不同的站是使用不同的 Newsgroup Name。</p>
    <p>本系統中，Newsgroup Name 固定為 <code>group.[<b>站台名稱</b>].[<b>群組名稱</b>]</code>，其中 [<b>站台名稱</b>] 的部分使用 <code>twbbs.org</code> 的網域名稱做為判別站台的方式，譬如 <code>ptt</code>、<code>zoo</code> 等等...。[<b>群組名稱</b>] 則是使用者自己選擇，可以使用 3 ~ 64 個字元 <font color="red">(小寫英文字母、數字、底線)</font>。</p>
  </li>

  <li>
    <h2>使用規定</h2>

    <p>使用規定的部分，請參考申請時的使用規章。</p>
  </li>

  <li>
    <h2>使用方法 (BBS 轉信管理者部分)</h2>

    <p>請您先參考上方所描述的系統架構，請您申請 <code>twbbs.org</code> 的 Domain Name。申請以後，若使用者有設定您可以轉他的 Newsgroup，則 Newsgroup Name 為 <code>group.[<b>您的 twbbs.org 名稱</b>].[<b>使用者的群組名稱</b>]</code>。</p>
    <p>舉例來說，<code>Ptt.twbbs.org</code> 如果要轉 testboard 這個板，那麼他所要轉的 Groupname 則是：</p>
    <p><code>group.ptt.testboard</code></p>
    <p>轉信的 News Server 為 <code>group.nctu.edu.tw</code>，二十四小時內的抓取次數請維持在 144 次以下。</p>
  </li>

  <li>
    <h2>使用方法 (使用者部分)</h2>

    <p>Under Construction.</p><p>如果有任何問題，您可以寫信到 <a href="mailto:usenet@group.nctu.edu.tw"><code>usenet@group.nctu.edu.tw</code></a> 詢問，或於 <a href="news:tw.bbs.netnews"><code>tw.bbs.netnews</code></a> 討論。</p>
  </li>
</ul>

</body>

</html>
