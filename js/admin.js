const formHTML = document.querySelector("form").innerHTML;

function display()
{
    document.querySelector("form").classList.toggle("display");
    setTimeout(() => {document.querySelector("form").classList.toggle("effect")}, 0);
}

function undisplay()
{
    document.querySelector("form").classList.toggle("effect");
    setTimeout(() => {document.querySelector("form").classList.toggle("display");}, 500);
}

//QLSP
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

function displayProductDetails(productID, action)
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
            if(action == "showProduct")
            {
                form.querySelectorAll("input, select, textarea").forEach(element => {
                    element.disabled = true;
                });
                document.getElementById("update-btn").disabled = true;
                document.getElementById("update-btn").style.background = "#333";
                document.getElementById("update-btn").style.cursor = "no-drop";
            }
            
            
        }
    }
    xhr.send("action=showProduct&productID=" + productID);
}

function displaySubcategories()
{
    const categoryID = document.getElementById("categories").value;
    const formData = new FormData();
    formData.append("action", "displaySubcategories");
    formData.append("categoryID", categoryID);
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            
            const response = xhr.responseText;
            const subcategoriesSelect = document.getElementById("subcategories");
            subcategoriesSelect.innerHTML = response;
        }
    }
    xhr.send(formData);
}

function addProduct() //THÊM SẢN PHẨM
{
    
    if(!validateAddProduct())
    {
        return;
    }
    const formData = new FormData();
    const productName = document.getElementById("name").value;
    const productPrice = document.getElementById("price").value;
    const productHot = document.getElementById("hot").checked ? 1 : 0;
    const productSize = document.querySelectorAll("input[name='size']:checked");
    const selectedSizes = Array.from(productSize).map(size => `${size.value}`).join(",");
    const productDescribe = document.getElementById("describe").value;
    const quantity = document.getElementById("quantity").value;
    const categoryID = document.getElementById("categories").value;
    const subcategoryID = document.getElementById("subcategories").value;
    const images = document.getElementById("images").files;
    for(let i = 0; i < images.length; i++)
    {
        formData.append("productImages[]", images[i]);
    }
    formData.append("action", "add");
    formData.append("productName", productName);
    formData.append("productPrice", productPrice);
    formData.append("productHot", productHot);
    formData.append("productSize", selectedSizes);
    formData.append("productDescribe", productDescribe);
    formData.append("productQuantity", quantity);
    formData.append("categoryID", categoryID);
    formData.append("subcategoryID", subcategoryID);
    alert(subcategoryID);
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            const response = xhr.responseText;
            alert(response);
            clearProduct();
        }
    }
    xhr.send(formData);
}

function validateAddProduct()
{
    const productName = document.getElementById("name").value;
    const productPrice = document.getElementById("price").value;
    const productSize = document.querySelectorAll("input[name='size']:checked");
    const productDescribe = document.getElementById("describe").value;
    const quantity = document.getElementById("quantity").value;
    const categoryID = document.getElementById("categories").value;
    const subcategoryID = document.getElementById("subcategories").value;
    const images = document.getElementById("images").files;
    if(productName == "" || productPrice == "" || productDescribe == "" || quantity == "" || categoryID == "" || subcategoryID == "")
    {
        alert("Vui lòng điền đầy đủ thông tin");
        return false;
    }
    if(!productPrice.match(/^[0-9]+$/))
    {
        alert("Giá phải là số");
        return false;
    }
    if(productSize.length == 0)
    {
        alert("Vui lòng chọn ít nhất một size");
        return false;
    }
    if(!quantity.match(/^[0-9]+$/))
    {
        alert("Số lượng phải là số");
        return false;
    }
    if(images.length == 0)
    {
        alert("Phải chọn ít nhất 1 file hình ảnh");
        return false;
    }

    alert("Hợp lệ");
    return true;
}

function deleteProduct(productID, button)
{
    const formData = new FormData();
    formData.append("action", "deleteProduct");
    formData.append("productID", productID);
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function() 
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            const response = JSON.parse(xhr.responseText);
            if(response.success == 1)//xoa thanh cong
            {
                button.closest("tr").remove();
                alert("Xoa thành công" + productID);
            }
            else
            {
                alert("Loi khi xoa" + productID);
            }
        }
    }
    xhr.send(formData);
}

function updateProduct(productID)
{
    const formData = new FormData();
    formData.append("action", "updateProduct");
    formData.append("productID", productID);
 
    const productName = document.getElementById("name").value;
    const productPrice = document.getElementById("price").value;
    const productHot = document.getElementById("hot").checked ? 1 : 0;
    const productSize = document.querySelectorAll("input[name='size']:checked");
    const selectedSizes = Array.from(productSize).map(size => `${size.value}`).join(",");
    const productDescribe = document.getElementById("describe").value;
    const quantity = document.getElementById("quantity").value;
    const categoryID = document.getElementById("categories").value;
    const subcategoryID = document.getElementById("subcategories").value;
    /* const images = document.getElementById("images").files;
    for(let i = 0; i < images.length; i++)
    {
        formData.append("productImages[]", images[i]);
    } */
    formData.append("productName", productName);
    formData.append("productPrice", productPrice);
    formData.append("productHot", productHot);
    formData.append("productSize", selectedSizes);
    formData.append("productDescribe", productDescribe);
    formData.append("productQuantity", quantity);
    formData.append("categoryID", categoryID);
    formData.append("subcategoryID", subcategoryID);

    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function ()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            alert(xhr.responseText);
            location.reload();
        }
    }
    xhr.send(formData);
}

function clearProduct() // XOA TRUOC KHI THÊM
{
    document.querySelector("form").innerHTML = formHTML;
    document.getElementById("name").value = "";
    document.getElementById("price").value = "";
    document.getElementById("describe").value = "";
    document.getElementById("quantity").value = "";
    
    const div = document.createElement("div");
    div.classList.add("properties");
    div.innerHTML = `<label for="">Chọn ảnh: </label>
                    <input type="file" id="images" name="productImages[]" multiple accept="image/*">`;
    document.querySelector("form").appendChild(div);
    document.getElementById("images").addEventListener("change", (e) => {
        const images = e.target.files;
        for(let i = 0; i < images.length; i++)
            {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(images[i]);
                img.style.width = "50px";
                img.style.height = "50px";
                div.appendChild(img);
            }
    }); 
}

//QLKH
function displayCostomerDetails(userID, action)
{
    display();
    const formData = new FormData();
    formData.append("action", "showCostomer");
    formData.append("userID", userID);
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            const form = document.querySelector("form");
            form.innerHTML = xhr.responseText;
            if(action == "showCostomer")
                {
                    form.querySelectorAll("input, select, textarea").forEach(element => {
                        element.disabled = true;
                    });
                    document.getElementById("update-btn").disabled = true;
                    document.getElementById("update-btn").style.background = "#333";
                    document.getElementById("update-btn").style.cursor = "no-drop";
                }
                else
                {
                    document.getElementById("create-time").disabled = true;
                    document.getElementById("image-container").innerHTML += `<input id="image" type="file" accept="image/*">`;
                    document.getElementById("image").addEventListener("change", () => 
                    {
                        document.getElementById("current-image").src = URL.createObjectURL(document.getElementById("image").files[0]);
                    });
                }
                
        }
    }
    xhr.send(formData);
}

function clearCostomer()
{
    const form = document.querySelector("form");
    form.innerHTML = formHTML;
}

function addCostomer()
{
    const fullName = document.getElementById("name").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const address = document.getElementById("address").value;
    const wallet = document.getElementById("wallet").value;
    const image = document.getElementById("image").files[0];


    const formData = new FormData();
    formData.append("action", "addCostomer");
    formData.append("fullName", fullName);
    formData.append("username", username);
    formData.append("password", password);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("address", address);
    formData.append("wallet", wallet);
    formData.append("image", image);


    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            alert(xhr.responseText);
        }
    }
    xhr.send(formData);
}

function deleteCostomer(userID, button)
{
    const formData = new FormData();
    formData.append("action", "deleteCostomer");
    formData.append("userID", userID);

    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            button.closest("tr").remove();
            alert("Xóa thành công "+ xhr.responseText);
        }
    }
    xhr.send(formData);
}

function updateCostomer(userID)
{   
    const fullName = document.getElementById("name").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const address = document.getElementById("address").value;
    const wallet = document.getElementById("wallet").value;
    let image = document.getElementById("image").files[0];
    

    const formData = new FormData();
    formData.append("action", "updateCostomer");
    formData.append("fullName", fullName);
    formData.append("username", username);
    formData.append("password", password);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("address", address);
    formData.append("wallet", wallet);
    if(!image)
    {
        alert("rong");
    }
    else
    {
        formData.append("image", image);
    }
    formData.append("userID", userID);


    xhr = new XMLHttpRequest();
    xhr.open("POST", "../admin/admin.php");
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            alert(xhr.responseText);
        }
    }
    xhr.send(formData);
}