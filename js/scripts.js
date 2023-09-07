class Item {
    constructor(id, name, done) {

        this.element = null;

        this.id = id;
        this.name = name;
        this.done = done;

        this.createEmptyElement();
    }

    createEmptyElement() {
        this.element = $(`
        <div class="item row" id="${this.id}">
            <div class="col-1">
                <input class="form-check-input" type="checkbox"/>
            </div>
            <div class="name col-9">${this.name}</div>
            <div class="options col-2">
                <button class="btn btn-secondary">Edit</button>
                <button class="btn btn-danger">Delete</button>
            </div>
        </div>
        `)
    }

    toggleDone() {

    }

    delete() {

    }

    edit() {

    }
}

class List {
    constructor($element) {
        this.element = $element;
        this.itemList = [];
    }

    addItem(item) {
        this.itemList.push(item);
        this.element.append(item.element);
    }
}


class ItemForm {
    constructor($element) {
        this.element = $element;

        $(this.element).find("button").click((e) => {
            e.preventDefault();
            this.postNewItem(this.element.find("input").get(0).value);
        });
    }

    postNewItem(name) {
        $.ajax({
            url: "api/post.php",
            type: "POST",
            data: {
                name: name
            }
        }).done((response) => {
            console.log(response);
            if (response.success) {
                this.element.find("input").get(0).value = "";
                let newItem = new Item(response.data.id, response.data.name, response.data.done);
                itemList.addItem(newItem);
            }
        }).fail(function(obj){
            console.log(obj.statusText);
        });
    }
}

var itemList;
var itemForm;

$(document).ready(function () {
    itemList = new List($("#itemList"));
    itemForm = new ItemForm($("#itemForm"));
});