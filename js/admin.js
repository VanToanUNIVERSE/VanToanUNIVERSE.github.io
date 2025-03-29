function display()
{
    document.querySelector("form").classList.toggle("display");
    setTimeout(() => {document.querySelector("form").classList.toggle("effect");}, 0);
}

function undisplay()
{
    document.querySelector("form").classList.toggle("effect");
    setTimeout(() => {document.querySelector("form").classList.toggle("display");}, 500);
}

function searchProduct()// TIM KIEM
{
    const searchQuery = document.getElementById("search").value;
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            const table = document.querySelector("table");
            table.innerHTML = "";
            const response = xhr.responseText;
            table.innerHTML = response;
        }
    }
    xhr.send("searchQuery=" + searchQuery);
}

function displayProductDetails(productID)
{
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            const response = xhr.responseText;
            const form = document.querySelector("form");
            form.innerHTML = response;
            form.querySelectorAll("input, select, textarea").forEach(element => {
                element.disabled = true;
            });
            
        }
    }
    xhr.send("action=show&productID=" + productID);
}

function addProduct()
{
    const productID = document.getElementById("id").value;
    const productName = document.getElementById("name").value;
    const productPrice = document.getElementById("price").value;
    const productHot = document.getElementById("hot").checked;
    const productSize = document.querySelectorAll("input[name='size']:checked");
    const selectedSizes = Array.from(productSize).map(size => `'${size.value}'`).join(", ");
    const productDescribe = document.getElementById("describe").value;
    const quantity = document.getElementById("quantity");
    console.log(selectedSizes);
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            const response = xhr.responseText;
            console.log(response);
        }
    }
    xhr.send("action=add&productID=" + productID + "&productName=" + productName + "&productPrice=" + productPrice + "&productHot=" + productHot + "&productSize=" + selectedSizes + "&productDescribe=" + productDescribe + "&productQuantity=" + quantity);
}

