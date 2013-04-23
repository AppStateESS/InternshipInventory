	var Faculty = Backbone.Model.extend({
		defaults: function() {
			return {
				name: "empty name",
				banner_id: "900123456"
			};
		}
	});
	
	var FacultyList = Backbone.Collection.extend({
		model: Faculty,
		department: '0',
		url: function() {
			return 'index.php?module=intern&action=getFacultyListForDept&department=' + this.department;
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
		      return this;
		}
	});
	
	var FacultyListView = Backbone.View.extend({
		initialize: function() {
			this.collection = new FacultyList;
			
			this.listenTo(this.collection, 'add', this.addOne);
			this.listenTo(this.collection, 'reset', this.addAll);
		    this.listenTo(this.collection, 'all', this.render);
			},
		render: function() {
			
			},
		addOne: function(faculty) {
			var view = new FacultyView({model: faculty});
			
			this.el.append(view.render().el);
		},
		addAll: function() {
			this.collection.each(this.addOne, this);
		}
	});
