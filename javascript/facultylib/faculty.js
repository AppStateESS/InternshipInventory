$(function() {

    var Faculty = Backbone.Model.extend({
        defaults: {
            id: null,
            username: null,
            first_name: null,
            last_name: null,
            phone: null,
            fax: null,
            street_address1: null,
            street_address2: null,
            city: null,
            state: null,
            zip: null
        }
    });

    var FacultyCollection = Backbone.Collection.extend({
        model: Faculty,
        department: '0',
        url: function() {
            return 'index.php?module=intern&action=getFacultyListForDept&department=' + this.department;
        },
        initialize: function(models, options) {
            if(!options.department) {
                throw 'Please pass a department in options to new FacultyCollection';
            }

            this.department = options.department;
        }
    });

    var FacultyView = Backbone.View.extend({
        tagName: 'li',
        template:_.template($('#faculty-template').html()),
        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);
        },
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            var me = this;
            $('.faculty-edit', this.$el).bind('click', function(e) { me.edit.call(me, e); });
            return this;
        },
        edit: function(e) {
            var dialog = new FacultyEditView({model: this.model});
            dialog.render();
        }
    });

    var FacultyCollectionView = Backbone.View.extend({
        el: '<ul>',
        initialize: function(options) {
            this.collection = options.collection;

            this.listenTo(this.collection, 'add', this.addOne);
            this.listenTo(this.collection, 'reset', this.addAll);
            this.listenTo(this.collection, 'all', this.render);
        },
        render: function() {
            this.addAll();
            return this;
        },
        addOne: function(faculty) {
            var view = new FacultyView({model: faculty});
            this.$el.append(view.render().el);
        },
        addAll: function() {
            this.$el.empty();
            this.collection.each(this.addOne, this);
        }
    });

    var FacultyEditView = Backbone.View.extend({
        template: _.template($('#faculty-edit-dialog-template').html()),
        events: {
            'remove': 'cleanup'
        },
        initialize: function(options) {
            this.model = options.model;
        },
        render: function() {
            var me = this;

            // Title changes depending on how new the model is
            var title = 'Add a Faculty Member';
            if(this.model.get('id')) {
                title = 'Edit a Faculty Member';
            }

            // Render template and open dialog
            this.$el.html(this.template(this.model.toJSON())).dialog({
                title: title,
                autoOpen: true,
                modal: true,
                width: 500,
                height: 600,
                buttons: [
                    {
                        text: 'Save',
                        click: function(e) {
                            return me.add.call(me, e);
                        }
                    },
                    {
                        text: 'Cancel',
                        click: function(e) {
                            return me.cancel.call(me, e);
                        }
                    }]
            });

            // Refer to DOM elements that will be used later
            this.$id = $('#faculty-edit-id', this.$el);
            this.$manualentry = $('.manual-entry', this.$el);
            // Same and hide by default
            this.$editmoredata = $('.edit-more-data', this.$el).hide();
            this.$promptmoredata = $('.prompt-more-data', this.$el).hide();
            this.$loadingmoredata = $('.loading-more-data', this.$el).hide();

            // If we get to the manual entry button, it should show elements
            this.$manualentry.bind('click', function (e) { me.manualEntry.call(me, e); });

            if(this.model.get('id')) {
                // Show "more data" if faculty exists already at this point
                this.$editmoredata.show();
            } else {
                // Expect user to enter an id if this is truly new
                this.$id.bind('keyup', function (e) { me.idKeypress.call(me, e); });
            }

            return this;
        },
        add: function(e) {
//            this.collection.add(this.model);
//            this.collection.save();
            // TODO: handle errors
            this.remove();
        },
        cancel: function(e) {
            this.remove();
        },
        cleanup: function(e) {
            this.$el.dialog('destroy');
            this.$el.children().remove();
        },
        manualEntry: function(e) {
            this.$promptmoredata.hide();
            this.$editmoredata.show();
        },
        idKeypress: function(e) {
            if(this.keyPressTimeout) {
                clearTimeout(this.keyPressTimeout);
            }

            var me = this;
            this.keyPressTimeout = setTimeout(function () {
                me.$loadingmoredata.show();
                // TODO: ajax
                setTimeout(function() {
                    me.$loadingmoredata.hide();
                    me.$promptmoredata.show();
                }, 1000);
            }, 500);
        },
    });

    var FacultyManagementView = Backbone.View.extend({
        initialize: function() {
            this.$department = $('#department');
            this.$newbutton = $('#faculty-new');
            this.$listholder = $('#faculty-list');
            this.$loading = $('.faculty-loading').hide();

            var me = this;

            this.$department.bind('change', function(e) {
                me.select.call(me, e);
            });
            this.$newbutton.bind('click', function(e) {
                me.add.call(me, e);
            });

            this.$newbutton.hide();
        },
        render: function() {
            var me = this;
        },
        add: function(e) {
            var dialog = new FacultyEditView({model: new Faculty()});
            dialog.render();
        },
        select: function(e) {
            if(this.$department.val() == -1) {
                this.$newbutton.hide();

                if(this.listView) {
                    this.listView.remove();
                    delete this.listView;
                }

                return;
            }

            var me = this;

            this.$newbutton.show();

            delete this.collection;

            this.collection = new FacultyCollection([],{department: this.$department.val()});
            this.collection.fetch();
            this.$loading.show();

            var me = this;
            this.collection.fetch({
                success: function (collection, response, options) {
                    me.$loading.hide();
                    me.listView = new FacultyCollectionView({collection: collection});
                    me.$listholder.html(me.listView.$el);
                }
            });

        }
    });

    var FacultyFormView = Backbone.View.extend({
    });

    window.FacultyManagementView = FacultyManagementView;
    window.FacultyFormView = FacultyFormView;
});
