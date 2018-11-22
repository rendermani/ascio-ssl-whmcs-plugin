<style>
    .failed div { color:#9e0600}
    .Completed div { color: #516402}
    #order-status .panel { padding:10px; }
</style>

<h1>Register SSL Certificate</h1>
<h2>{$common_name}</h2>

{if is_array($errors)}
    <div class="alert alert-danger" role="alert">
        <h3>Status: <b>{$message}</b></h3>
        {foreach from=$errors item=error}
            <p>{$error}</p>
        {/foreach}
    </div>
{else}
    <div id="order-status" data-serviceid="{$whmcs_service_id}" >
       {$statusHtml}
    </div>

{/if}

<script type="text/javascript" src="/whmcs/modules/servers/asciossl/asciossl.js"></script>

