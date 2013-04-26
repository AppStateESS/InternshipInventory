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
        },
        url: function() {
            return 'index.php?module=intern&action=restFacultyById&id=' + this.get('id');
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
            this.$('.faculty-edit').bind('click', function(e) { me.edit.call(me, e); });
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

    var BannerIDRegex = /^9\d{8}$/;

    var FacultyEditView = Backbone.View.extend({
        el: '<div>',
        template: _.template($('#faculty-edit-dialog-template').html()),
        events: {
            'remove': 'cleanup'
        },
        initialize: function(options) {
            this.model = options.model;
            this.listenTo(this.model, 'sync', this.render);
            this.firstRender = true;
            this.createNew = !this.model.get('id');
        },
        render: function() {
            var me = this;

            if(this.firstRender) {
                this.firstRender = false;

                var title = this.createNew ? 'Add a Faculty Member' : 'Edit a Faculty Member';
                this.$el.dialog({
                    title: title,
                    autoOpen: true,
                    modal: true,
                    width: 500,
                    height: 600,
                    buttons:
                        [
                            {
                                text: 'Save',
                                click: function(e) {
                                    return me.save.call(me, e);
                                }
                            },
                            {
                                text: 'Cancel',
                                click: function(e) {
                                    return me.cancel.call(me, e);
                                }
                            }
                        ]
                });
            }

            this.$el.html(this.template(this.model.toJSON()));

            // Refer to DOM elements that will be used later
            this.$id = this.$('#faculty-edit-id');
            this.$manualentry = this.$('.manual-entry');
            // Same and hide by default
            this.$editmoredata = this.$('.edit-more-data').hide();
            this.$promptmoredata = this.$('.prompt-more-data').hide();
            this.$loadingmoredata = this.$('.loading-more-data').hide();
            this.$ifnew = this.$('.faculty-show-new').hide();
            this.$ifedit = this.$('.faculty-show-edit').hide();

            // If we get to the manual entry button, it should show elements
            this.$manualentry.bind('click', function (e) { me.manualEntry.call(me, e); });

            if(this.model.get('id')) {
                // Show "more data" if faculty exists already at this point
                this.$editmoredata.show();
            } else {
                // Expect user to enter an id if this is truly new
                this.$id.bind('keyup', function (e) { me.idKeypress.call(me, e); });
            }

            if(this.createNew) {
                this.$ifnew.show();
            } else {
                this.$ifedit.show();
            }

            return this;
        },
        cleanup: function(e) {
            this.$el.dialog('destroy');
            this.$el.children().remove();
        },
        save: function(e) {
            this.model.save();
            // TODO: handle errors
            // TODO: fire event
            this.remove();
        },
        cancel: function(e) {
            this.remove();
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
                if(!BannerIDRegex.test(me.$id.val())) return;
                
                me.$loadingmoredata.show();

                me.model.set('id', me.$id.val());

                me.model.fetch({
                    success: function (collection, response, options) {
                        me.createNew = false;
                    },
                    error: function (collection, response, options) {
                        me.$promptmoredata.show();
                    },
                    complete: function () {
                        me.$loadingmoredata.hide();
                    }
                });
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
