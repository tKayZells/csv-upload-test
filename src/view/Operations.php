<p>File found on server:</p>
<form action="/" method="post" enctype="multipart/form-data">
    Indicate an id to lookup:
    <input type="number" name="csv_id" id="csv_id" >
    <input type="submit" value="Look up" name="submit">
    <input type="hidden" name="lookup" value="1" />
</form>   
<br />

<form action="/" method="post">
    <input type="submit" name="fetch_incorrect_data_action" value="Incorrect Data Search" /> 
</form>
<br />

<form action="/" method="post">
    <input type="submit" name="fetch_duplicate_data" value="Duplicate Data Search" /> 
</form>
<br />