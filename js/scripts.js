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
        <div class="item row my-2 ${this.done ? "done" : ""}" id="${this.id}">
            <div class="name col-10 d-flex align-items-center py-2">
                <input class="form-check-input me-4" type="checkbox" ${this.done ? "checked='checked'" : ""}/>
                <span contenteditable="true">${this.name}</span>
            </div>
            <div class="options col-2 d-flex align-items-center justify-content-end">
                <button class="edit btn btn-sm btn-secondary me-2">Edit</button>
                <button class="delete btn btn-sm btn-danger">Delete</button>
            </div>
        </div>
        `);
        this.initEvents();
    }

    initEvents() {
        this.element.find("button.delete").click((e) => {
            e.preventDefault();
            this.delete();
        });
        this.element.find("button.edit").click((e) => {
            e.preventDefault();
            this.focusName();
        });
        this.element.find("div.name>span").blur((e) => {
            this.saveNewName();
        });
        this.element.find("div.name>span").keypress((e) => {
            if (e.which == 13) {
                this.element.find("div.name>span").blur();
            }
        });
        this.element.find("div.name>input").change((e) => {
            this.toggleDone();
        });
    }

    focusName() {
        this.element.find("div.name>span").focus();
    }

    toggleDone() {
        $.ajax({
            url: "api/toggleDone",
            type: "POST",
            data: {
                id: this.id
            }
        }).done((response) => {
            if (response.success) {
                this.element.find("div.name>input").prop("checked", response.data.done);
                this.element.toggleClass("done", response.data.done);
            }
        }).fail(function(obj){
            console.log(obj.statusText);
        });
    }

    delete() {
        $.ajax({
            url: "api/delete",
            type: "POST",
            data: {
                id: this.id
            }
        }).done((response) => {
            if (response.success) {
                this.element.remove();
            }
        }).fail(function(obj){
            console.log(obj.statusText);
        });
    }

    saveNewName() {
        $.ajax({
            url: "api/edit",
            type: "POST",
            data: {
                id: this.id,
                name: this.element.find("div.name>span").text()
            }
        }).done((response) => {
            if (response.success) {
                this.name = response.data.name;
                this.element.find("div.name>span").text(this.name);
            } else {
                this.element.find("div.name>span").text(this.name);
            }
        }).fail(function(obj){
            console.log(obj.statusText);
        });
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

    loadItems() {
        $.ajax({
            url: "api/list",
            type: "GET"
        }).done((response) => {
            if (response.success) {
                response.data.forEach(itemData => {
                    let newItem = new Item(itemData.id, itemData.name, itemData.done);
                    itemList.addItem(newItem);
                });
            }
        }).fail(function(obj){
            console.log(obj.statusText);
        });
    }
}


class ItemForm {
    constructor($element) {
        this.element = $element;

        this.element.find("button").click((e) => {
            e.preventDefault();
            this.element.submit();
        });

        this.element.submit((e) => {
            e.preventDefault();
            this.postNewItem(this.element.find("input").get(0).value);
        })
    }

    postNewItem(name) {
        $.ajax({
            url: "api/post",
            type: "POST",
            data: {
                name: name
            }
        }).done((response) => {
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

    itemList.loadItems();
});