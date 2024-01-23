const dataTable = document.getElementById('dataTable');
const pageHeader = document.getElementById('pageHeader');
const categoryForm = document.getElementById('categoryForm');
const vendorForm = document.getElementById('vendorForm');
const propertyForm = document.getElementById('propertyForm');
const tabletForm = document.getElementById('tabletForm');
const tabletLink = document.getElementById('tabletLink');
const categoryLink = document.getElementById('categoryLink');
const vendorLink = document.getElementById('vendorLink');
const propertyLink = document.getElementById('propertyLink');
const logoutLink = document.getElementById('logoutLink');
const categorySelect = document.getElementById('tabletCategoryInput');
const vendorSelect = document.getElementById('tabletVendorInput');
const contentContainer = document.getElementById('contentContainer');
const loginContainer = document.getElementById('loginContainer');
const loginForm = document.getElementById('loginForm');
const searchForm = document.getElementById('searchForm');
const loginErrorText = document.getElementById('loginErrorText');
const categoryErrorText = document.getElementById('categoryErrorText');
const vendorErrorText = document.getElementById('vendorErrorText');
const categoryDeleteErrorText = document.getElementById('categoryDeleteErrorText');
const vendorDeleteErrorText = document.getElementById('vendorDeleteErrorText');
const propertyErrorText = document.getElementById('propertyErrorText');
const propertyDeleteErrorText = document.getElementById('propertyDeleteErrorText');
const propInputsContainer = document.getElementById('propInputsContainer');
function checkLogin() {
    fetch('http://localhost/lab/app/api/loginController.php')
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            console.log(data);
            if (data.userlogin == '') {
                loginContainer.style.display = 'flex';
            } else {
                getVendors();
                getCategories();
                getProperties();
                getTablets();
                contentContainer.style.display = 'flex';
            }
        });
}
function getCategories() {
    fetch('http://localhost/lab/app/api/categoryController.php')
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            [].forEach.call(document.querySelectorAll('.app-form'), function (el) {
                el.style.display = 'none';
            });
            categoryForm.style.display = 'block';
            //console.log(data);
            pageHeader.innerText = 'Категорії';
            let content = ``;
            let selectContent = ``;
            for (let i = 0; i < data.length; i++) {
                selectContent += `<option value="` + data[i].id + `">` + data[i].name + `</option>`
                content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].name + `</td>
                        <td>
                            <a class='edit-category' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                            <a class='delete-category' data-id="`+ data[i].id + `" href="#">Видалити</a>
                        </td>
            </tr>`;
            }
            dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
            categorySelect.innerHTML = selectContent;
        });
}

function getVendors() {
    fetch('http://localhost/lab/app/api/vendorController.php')
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            [].forEach.call(document.querySelectorAll('.app-form'), function (el) {
                el.style.display = 'none';
            });
            vendorForm.style.display = 'block';
            //console.log(data);
            pageHeader.innerText = 'Виробники';
            let content = ``;
            let selectContent = ``;
            for (let i = 0; i < data.length; i++) {
                selectContent += `<option value="` + data[i].id + `">` + data[i].vendor + `</option>`
                content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].vendor + `</td>
                        <td>
                            <a class='edit-vendor' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                            <a class='delete-vendor' data-id="`+ data[i].id + `" href="#">Видалити</a>
                        </td>
            </tr>`;
            }
            dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
            vendorSelect.innerHTML = selectContent;
        });
}

function getProperties() {
    fetch('http://localhost/lab/app/api/propertyController.php')
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            //console.log(data);
            [].forEach.call(document.querySelectorAll('.app-form'), function (el) {
                el.style.display = 'none';
            });
            propertyForm.style.display = 'block';
            pageHeader.innerText = 'Характеристики';
            let content = ``;
            let inputContent = ``
            for (let i = 0; i < data.length; i++) {
                content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].name + `</td>
                        <td>`+ data[i].units + `</td>
                        <td>
                            <a class='edit-property' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                            <a class='delete-property' data-id="`+ data[i].id + `" href="#">Видалити</a>
                        </td>
            </tr>`;
                inputContent += `<p><input placeholder="` + data[i].name + `" data-id="` + data[i].id + `" class="prop-input" required/></p>`
            }
            dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Одиниці вимірювання</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
            propInputsContainer.innerHTML = inputContent;
        });
}
function getTablets() {
    fetch('http://localhost/lab/app/api/tabletController.php')
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            //console.log(data);
            [].forEach.call(document.querySelectorAll('.app-form'), function (el) {
                el.style.display = 'none';
            });
            tabletForm.style.display = 'block';
            pageHeader.innerText = 'Графічні планшети';
            let content = ``;
            for (let i = 0; i < data.length; i++) {
                let propertyContent = ``;
                for (let j = 0; j < data[i].properties.length; j++) {
                    propertyContent += data[i].properties[j].name + `: ` + data[i].properties[j].value + ` ` + data[i].properties[j].units + `</br>`;
                }
                content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].vendor + `</td>
                        <td>`+ data[i].name + `</td>
                        <td>`+ data[i].category + `</td>
                        <td>`+ data[i].price + `</td>
                        <td>`+ propertyContent + `</td>
                        <td>
                            <a class='edit-tablet' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                            <a class='delete-tablet' data-id="`+ data[i].id + `" href="#">Видалити</a>
                        </td>
            </tr>`;
            }
            dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Виробник</th>
                                <th>Назва</th>
                                <th>Ціна</th>
                                <th>Категорія</th>
                                <th>Характеристики</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
        });
}
checkLogin();

/*categoryForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let categoryName=document.getElementById('categoryNameInput').value;
    let categoryId=document.getElementById('categoryIdInput').value;
    if(categoryId==''){
    let formData = new FormData();
    formData.append('name', categoryName);
    fetch("http://localhost/lab/app/api/categoryController.php",
        {
            body: formData,
            method: "POST"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            categoryForm.reset();
            getCategories();
        });
    } else{
        requestData={id:categoryId, name:categoryName};
        fetch("http://localhost/lab/app/api/categoryController.php",
        {
            body: JSON.stringify(requestData),
            method: "PUT"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            categoryForm.reset();
            getCategories();
            document.getElementById('categoryIdInput').value=''
        });
    }
  });*/

categoryForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let categoryName = document.getElementById('categoryNameInput').value;
    let categoryId = document.getElementById('categoryIdInput').value;
    if (categoryId == '') {
        let formData = new FormData();
        formData.append('name', categoryName);
        fetch("http://localhost/lab/app/api/categoryController.php",
            {
                body: formData,
                method: "POST"
            }).then((response) => {
                return response.json();
            }).then((data) => {
                if (data.status == "error") {
                    // Вивести помилку
                    categoryErrorText.innerText = data.message;
                    setTimeout(() => {
                        categoryErrorText.innerText = '';
                    }, 3000);
                } else {
                    categoryForm.reset();
                    getCategories();
                }
            });
    } else {
        requestData = { id: categoryId, name: categoryName };
        fetch("http://localhost/lab/app/api/categoryController.php",
            {
                body: JSON.stringify(requestData),
                method: "PUT"
            }).then((response) => {
                return response.json();
            }).then(() => {
                categoryForm.reset();
                getCategories();
                document.getElementById('categoryIdInput').value = ''
            });
    }
});



/*vendorForm.addEventListener("submit", (event) => {
  event.preventDefault();
  let vendorName=document.getElementById('vendorNameInput').value;
  let vendorId=document.getElementById('vendorIdInput').value;
  if(vendorId==''){
  let formData = new FormData();
  formData.append('vendor', vendorName);
  fetch("http://localhost/lab/app/api/vendorController.php",
      {
          body: formData,
          method: "POST"
      }).then((response) => {
          return response.json();
      }).then(()=>{
          vendorForm.reset();
          getVendors();
      });
  } else{
      requestData={id:vendorId, vendor:vendorName};
      fetch("http://localhost/lab/app/api/vendorController.php",
      {
          body: JSON.stringify(requestData),
          method: "PUT"
      }).then((response) => {
          return response.json();
      }).then(()=>{
          vendorForm.reset();
          getVendors();
          document.getElementById('vendorIdInput').value=''
      });
  }
}); */

vendorForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let vendorName = document.getElementById('vendorNameInput').value;
    let vendorId = document.getElementById('vendorIdInput').value;
    if (vendorId == '') {
        let formData = new FormData();
        formData.append('vendor', vendorName);
        fetch("http://localhost/lab/app/api/vendorController.php",
            {
                body: formData,
                method: "POST"
            }).then((response) => {
                return response.json();
            }).then((data) => {
                if (data.status == "error") {
                    // Вивести помилку
                    vendorErrorText.innerText = data.message;
                    setTimeout(() => {
                        vendorErrorText.innerText = '';
                    }, 3000);
                } else {
                    vendorForm.reset();
                    getVendors();
                }
            });
    } else {
        requestData = { id: vendorId, vendor: vendorName };
        fetch("http://localhost/lab/app/api/vendorController.php",
            {
                body: JSON.stringify(requestData),
                method: "PUT"
            }).then((response) => {
                return response.json();
            }).then(() => {
                vendorForm.reset();
                getVendors();
                document.getElementById('vendorIdInput').value = ''
            });
    }
});

/*propertyForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let propertyName=document.getElementById('propertyNameInput').value;
    let propertyUnits=document.getElementById('propertyUnitsInput').value;
    let propertyId=document.getElementById('propertyIdInput').value;
    if(propertyId==''){
    let formData = new FormData();
    formData.append('name', propertyName);
    formData.append('units', propertyUnits);
    fetch("http://localhost/lab/app/api/propertyController.php",
        {
            body: formData,
            method: "POST"
        }).then(()=>{
            propertyForm.reset();
            getProperties();
        });
    } else{
        requestData={id:propertyId, name:propertyName,units:propertyUnits};
        fetch("http://localhost/lab/app/api/propertyController.php",
        {
            body: JSON.stringify(requestData),
            method: "PUT"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            propertyForm.reset();
            getProperties();
            document.getElementById('propertyIdInput').value=''
        });
    }
  }); */


propertyForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let propertyName = document.getElementById('propertyNameInput').value;
    let propertyUnits = document.getElementById('propertyUnitsInput').value;
    let propertyId = document.getElementById('propertyIdInput').value;
    if (propertyId == '') {
        let formData = new FormData();
        formData.append('name', propertyName);
        formData.append('units', propertyUnits);
        fetch("http://localhost/lab/app/api/propertyController.php",
            {
                body: formData,
                method: "POST"
            }).then((response) => {
                return response.json();
            }).then((data) => {
                if (data.status == "error") {
                    // Вивести помилку
                    propertyErrorText.innerText = data.message;
                    setTimeout(() => {
                        propertyErrorText.innerText = '';
                    }, 3000);
                } else {
                    propertyForm.reset();
                    getProperties();
                }
            });
    } else {
        requestData = { id: propertyId, name: propertyName, units: propertyUnits };
        fetch("http://localhost/lab/app/api/propertyController.php",
            {
                body: JSON.stringify(requestData),
                method: "PUT"
            }).then((response) => {
                return response.json();
            }).then(() => {
                propertyForm.reset();
                getProperties();
                document.getElementById('propertyIdInput').value = ''
            });
    }
});

tabletForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let tabletVendor = document.getElementById('tabletVendorInput').value;
    let tabletName = document.getElementById('tabletNameInput').value;
    let tabletCategory = document.getElementById('tabletCategoryInput').value;
    let tabletPrice = document.getElementById('tabletPriceInput').value;
    let tabletId = document.getElementById('tabletIdInput').value;
    if (tabletId == '') {
        let formData = new FormData();
        formData.append('vendor', tabletVendor);
        formData.append('name', tabletName);
        formData.append('category', tabletCategory);
        formData.append('price', tabletPrice);
        var propInputs = document.getElementsByClassName("prop-input");
        for (var i = 0; i < propInputs.length; i++) {
            formData.append('prop_' + propInputs[i].getAttribute('data-id'), propInputs[i].value);
        }
        fetch("http://localhost/lab/app/api/tabletController.php",
            {
                body: formData,
                method: "POST"
            }).then(() => {
                tabletForm.reset();
                getTablets();
            });
    } else {
        requestData = { id: tabletId, vendor: tabletVendor, name: tabletName, price: tabletPrice, category: tabletCategory };
        var propInputs = document.getElementsByClassName("prop-input");
        for (var i = 0; i < propInputs.length; i++) {
            requestData['prop_' + propInputs[i].getAttribute('data-id')] = propInputs[i].value;
        }
        fetch("http://localhost/lab/app/api/tabletController.php",
            {
                body: JSON.stringify(requestData),
                method: "PUT"
            }).then((response) => {
                return response.json();
            }).then(() => {
                tabletForm.reset();
                getTablets();
                document.getElementById('tabletIdInput').value = ''
            });
    }
});
loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let login = document.getElementById('loginInput').value;
    let password = document.getElementById('passwordInput').value;
    let formData = new FormData();
    formData.append('login', login);
    formData.append('password', password);
    fetch("http://localhost/lab/app/api/loginController.php",
        {
            body: formData,
            method: "POST"
        }).then((response) => {
            return response.json();
        })
        .then((data) => {
            loginForm.reset();
            if (data.error == '') {
                loginContainer.style.display = 'none';
                contentContainer.style.display = 'flex';
                getCategories();
                getVendors();
                getProperties();
                getTablets();
            } else {
                loginErrorText.innerText = data.error;
            }
        });
});

searchForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let searchText = document.getElementById('searchInput').value;
    if (categoryForm.style.display == 'block') {
        fetch("http://localhost/lab/app/api/categoryController.php?search=" + searchText,
            {
                method: "GET"
            }).then((response) => {
                return response.json();
            })
            .then((data) => {
                pageHeader.innerText = 'Категорії';
                let content = ``;

                for (let i = 0; i < data.length; i++) {
                    content += `<tr>
                            <td>`+ data[i].id + `</td>
                            <td>`+ data[i].name + `</td>
                            <td>
                                <a class='edit-category' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                                <a class='delete-category' data-id="`+ data[i].id + `" href="#">Видалити</a>
                            </td>
                </tr>`;
                }
                dataTable.innerHTML = `<thead>
                                    <th>ID</th>
                                    <th>Назва</th>
                                    <th>Дії</th>
                                </thead>
                                <tbody>
                                    `+ content + `
                                </tbody>`;
            });

    } else if (vendorForm.style.display == 'block') {
        fetch("http://localhost/lab/app/api/vendorController.php?search=" + searchText,
            {
                method: "GET"
            }).then((response) => {
                return response.json();
            })
            .then((data) => {
                pageHeader.innerText = 'Виробники';
                let content = ``;

                for (let i = 0; i < data.length; i++) {
                    content += `<tr>
                                    <td>`+ data[i].id + `</td>
                                    <td>`+ data[i].vendor + `</td>
                                    <td>
                                        <a class='edit-vendor' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                                        <a class='delete-vendor' data-id="`+ data[i].id + `" href="#">Видалити</a>
                                    </td>
                        </tr>`;
                }
                dataTable.innerHTML = `<thead>
                                            <th>ID</th>
                                            <th>Назва</th>
                                            <th>Дії</th>
                                        </thead>
                                        <tbody>
                                            `+ content + `
                                        </tbody>`;
            });

    } else if (propertyForm.style.display == 'block') {
        fetch("http://localhost/lab/app/api/propertyController.php?search=" + searchText,
            {
                method: "GET"
            }).then((response) => {
                return response.json();
            })
            .then((data) => {
                pageHeader.innerText = 'Характеристики';
                let content = ``;

                for (let i = 0; i < data.length; i++) {
                    content += `<tr>
                            <td>`+ data[i].id + `</td>
                            <td>`+ data[i].name + `</td>
                            <td>`+ data[i].units + `</td>
                            <td>
                                <a class='edit-property' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                                <a class='delete-property' data-id="`+ data[i].id + `" href="#">Видалити</a>
                            </td>
                </tr>`;
                }
                dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Одиниці вимірювання</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
            });

    } else if (tabletForm.style.display == 'block') {
        fetch("http://localhost/lab/app/api/tabletController.php?search=" + searchText,
            {
                method: "GET"
            }).then((response) => {
                return response.json();
            })
            .then((data) => {
                pageHeader.innerText = 'Графічні планшети';
                let content = ``;
                for (let i = 0; i < data.length; i++) {
                    let propertyContent = ``;
                    for (let j = 0; j < data[i].properties.length; j++) {
                        propertyContent += data[i].properties[j].name + `: ` + data[i].properties[j].value + ` ` + data[i].properties[j].units + `</br>`;
                    }
                    content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].vendor + `</td>
                        <td>`+ data[i].name + `</td>
                        <td>`+ data[i].category + `</td>
                        <td>`+ data[i].price + `</td>
                        <td>`+ propertyContent + `</td>
                        <td>
                            <a class='edit-tablet' data-id="`+ data[i].id + `" href="#">Редагувати</a>
                            <a class='delete-tablet' data-id="`+ data[i].id + `" href="#">Видалити</a>
                        </td>
            </tr>`;
                }
                dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Виробник</th>
                                <th>Назва</th>
                                <th>Ціна</th>
                                <th>Категорія</th>
                                <th>Характеристики</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
            });
    }
    searchForm.reset();
});

tabletLink.addEventListener("click", (event) => {
    event.preventDefault();
    getTablets();
});
vendorLink.addEventListener("click", (event) => {
    event.preventDefault();
    getVendors();
});
categoryLink.addEventListener("click", (event) => {
    event.preventDefault();
    getCategories();
});
propertyLink.addEventListener("click", (event) => {
    event.preventDefault();
    getProperties();
});
logoutLink.addEventListener("click", (event) => {
    event.preventDefault();
    fetch('http://localhost/lab/app/api/loginController.php?action=logout')
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            loginContainer.style.display = 'flex';
            contentContainer.style.display = 'none';
            loginErrorText.innerText = '';
        });
});
document.body.addEventListener('click', function (e) {

    /*if (e.target.className === 'delete-category') {
        e.preventDefault();
        fetch("http://localhost/lab/app/api/categoryController.php?id="+e.target.getAttribute('data-id'),
        {
            method: "DELETE"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            getCategories();
        });
    }*/

    if (e.target.className === 'delete-category') {
        e.preventDefault();
        fetch("http://localhost/lab/app/api/categoryController.php?id=" + e.target.getAttribute('data-id'),
            {
                method: "DELETE"
            }).then((response) => {
                return response.json();
            }).then((data) => {
                if (data.status == "error") {
                    // Вивести помилку
                    categoryDeleteErrorText.innerText = data.message;
                    setTimeout(() => {
                        categoryDeleteErrorText.innerText = '';
                    }, 3000);
                } else {
                    getCategories();
                }
            });
    }

    else
        if (e.target.className === 'delete-vendor') {
            e.preventDefault();
            fetch("http://localhost/lab/app/api/vendorController.php?id=" + e.target.getAttribute('data-id'),
                {
                    method: "DELETE"
            }).then((response) => {
                return response.json();
            }).then((data) => {
                if (data.status == "error") {
                    // Вивести помилку
                    vendorDeleteErrorText.innerText = data.message;
                    setTimeout(() => {
                        vendorDeleteErrorText.innerText = '';
                    }, 3000);
                } else {
                    getVendors();
                }
            });
        } else
            if (e.target.className === 'delete-property') {
                e.preventDefault();
                fetch("http://localhost/lab/app/api/propertyController.php?id=" + e.target.getAttribute('data-id'),
                    {
                        method: "DELETE"
                    }).then((response) => {
                        return response.json();
                    }).then((data) => {
                        if (data.status == "error") {
                            // Вивести помилку
                            propertyDeleteErrorText.innerText = data.message;
                            setTimeout(() => {
                                propertyDeleteErrorText.innerText = '';
                            }, 3000);
                        } else {
                            getProperties();
                        }
                    });
            } else
                if (e.target.className === 'delete-tablet') {
                    e.preventDefault();
                    fetch("http://localhost/lab/app/api/tabletController.php?id=" + e.target.getAttribute('data-id'),
                        {
                            method: "DELETE"
                        }).then((response) => {
                            return response.json();
                        }).then(() => {
                            getTablets();
                        });
                } else if (e.target.className === 'edit-category') {
                    e.preventDefault();
                    fetch('http://localhost/lab/app/api/categoryController.php?id=' + e.target.getAttribute('data-id'))
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            document.getElementById('categoryNameInput').value = data.name;
                            document.getElementById('categoryIdInput').value = data.id;
                        });
                } else if (e.target.className === 'edit-vendor') {
                    e.preventDefault();
                    fetch('http://localhost/lab/app/api/vendorController.php?id=' + e.target.getAttribute('data-id'))
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            document.getElementById('vendorNameInput').value = data.vendor;
                            document.getElementById('vendorIdInput').value = data.id;
                        });
                } else if (e.target.className === 'edit-property') {
                    e.preventDefault();
                    fetch('http://localhost/lab/app/api/propertyController.php?id=' + e.target.getAttribute('data-id'))
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            document.getElementById('propertyUnitsInput').value = data.units;
                            document.getElementById('propertyNameInput').value = data.name;
                            document.getElementById('propertyIdInput').value = data.id;
                        });
                } else if (e.target.className === 'edit-tablet') {
                    e.preventDefault();
                    fetch('http://localhost/lab/app/api/tabletController.php?id=' + e.target.getAttribute('data-id'))
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            document.getElementById('tabletVendorInput').value = data.vendor_id;
                            document.getElementById('tabletNameInput').value = data.name;
                            document.getElementById('tabletPriceInput').value = data.price;
                            document.getElementById('tabletCategoryInput').value = data.category_id;
                            document.getElementById('tabletIdInput').value = data.id;
                            for (i = 0; i < data.properties.length; i++) {
                                document.querySelectorAll(".prop-input[data-id='" + data.properties[i].property_id + "']")[0].value = data.properties[i].value;
                            }
                            document.querySelectorAll(".prop-input[data-id='2']")[0];
                        });
                }
}, false);
