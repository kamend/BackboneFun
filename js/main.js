

$(function() {
    MyWeatherapp.boot($('#weather-app'));
});


(function() {
	console.log('blah');
var MyWeatherapp = {}


window.MyWeatherapp = MyWeatherapp;

MyWeatherapp.Weather = Backbone.Model.extend({

});



MyWeatherapp.Weathers = Backbone.Collection.extend({
    url: '/rest/weathers'
  });

var template = function(name) {
	return Handlebars.compile($('#'+name+'-template').html());
}

MyWeatherapp.Index = Backbone.View.extend({
	template: template('index'),
    initialize: function() {
      this.weathers = new MyWeatherapp.Weathers();
      this.weathers.on('all', this.render, this);
      this.weathers.fetch();
    },
    render: function() {

    	console.log(arguments);
    	this.$el.html(this.template(this));

 		this.weathers.each(function(weather) {
 			var view = new MyWeatherapp.Index.Weather({model: weather});
 			this.$el.append(view.render().el);
 		}, this);

    	var form = new MyWeatherapp.Index.Form({collection: this.weathers});
    	this.$('.cities').append(form.render().el);
      	return this;
    },
    count: function() {
      return this.weathers.length;
    }
});

MyWeatherapp.Index.Form = Backbone.View.extend({
	tagName: 'form',
	className: 'form-inline',
	template: template('index-form'),
	events: {
		"submit": "submit"
	},

	render: function() {
		this.$el.append(this.template(this));
		return this;
	},
	submit: function(event) {
		event.preventDefault();

		this.collection.create({
			city: this.$('input#city').val()
		});

	}
});

MyWeatherapp.Index.Weather = Backbone.View.extend({
	template: template('index-weather'),
	className: 'weather row',
	events: {
		"click .btn-delete" : "delete",
		"click .btn-edit": "edit",
		"click .btn-save": "save",
		"click .btn-cancel": "cancel"
	},
	render: function() {
		this.$el.html(this.template(this));
		return this;
	},
	city: function() {
		return this.model.get('city');
	},
	delete: function(event) {
		event.preventDefault();

		this.model.destroy();
	},
	edit: function(event) {
		event.preventDefault();

		this.$('span#cityname').toggleClass('hidden');
		this.$("input#city").toggleClass('hidden');
		this.$("span.edit-item").toggleClass('hidden');
		this.$("span.update-item").toggleClass('hidden');
	},
	cancel: function(event) {
		event.preventDefault();

		this.render();
	},
	save: function(event) {
		event.preventDefault();

		var cityName = this.$('input#city').val();
		this.model.set('city', cityName);
		this.model.save();
		this.render();
	}
});


MyWeatherapp.Router = Backbone.Router.extend({
	initialize: function(options){
		this.el = options.el;
	},
	routes: {
		"": "index"
	},
	index: function(){
		var view = new MyWeatherapp.Index();
		this.el.empty();
		this.el.append(view.render().el);
	}
});


MyWeatherapp.boot = function(container) {
	container = $(container);
	var router = new MyWeatherapp.Router({el:container});
	Backbone.history.start();
}	

})();




