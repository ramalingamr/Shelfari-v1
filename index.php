<html>
	<head>
	<title>Shelfari</title>
	  <link rel="stylesheet" href="styles/bookStyles.css" />  
	  <script src="js/jquery-1.9.1.js"></script>
	  <script src="js/jquery-ui.js"></script>
	  <script src="js/underscore.js"></script>
	  <script src="js/backbone.js"></script>
	  <script>
	  $(function() {
	    $( "input[type=submit],button" )
	      .button()
	       });
	  </script>
	</head>
	<body>
		<script id="bookTemplate" type="text/template">
		    <div class="bookStyle">
			<table>
				<tr>
					<td>Book Name : </td><td><input type="text" name="bookname" value="<%= bookname %>"></input></td>
				</tr>
				<tr>
					<td>Author Name : </td><td><input type="text" name="authorname" value="<%= authorname %>"></input></td>
				</tr>
				<tr>
					<td>Status : </td><td><input type="text" name="status" value="<%= status %>"></input></td>
				</tr>
			</table>
			<div align="center">
			<button class="update">Update</button>
			<button class="delete">Delete</button>
			</div>
			<br/>
		    </div>
		</script>
			
		<div style="width: 100%;">
			<div class="leftContainer">
				<div id="booksDiv">
					<div class="addBookStyle" id="addBook">
					    <table>
						<tr>
							<td>Book Name : </td><td><input type="text" name="bookname"></input></td>
						</tr>
						<tr>
							<td>Author Name : </td><td><input type="text" name="authorname"></input></td>
						</tr>
						<tr>
							<td>Status : </td><td><input type="text" name="status"></input></td>
						</tr>
					    </table>
					    <div align="center">
					    <button class="add">Add Book</button>
					    </div>
					    <br/>
					</div>
				</div>
			</div>
			<div class="rightContainer">
				<h3 align="center">Search</h3>
				<div id="searchBooksDiv">
					<table>
						<tr>
							<td>Book Name : </td><td><input type="text" name="bookname" class="searchVal"></input></td>
						</tr>
						<tr>
							<td>Author Name : </td><td><input type="text" name="authorname" class="searchVal"></input></td>
						</tr>
						<tr>
							<td>Status : </td><td><input type="text" name="status" class="searchVal"></input></td>
						</tr>
					</table>
					<div align="center">
					<button class="search" id="search">Search</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="loadingDiv" style="display:none">
			<p><img src="resources/images/loading.gif" >Loading</p>
  		</div>
		<script>
		(function($) {
			$(document).ajaxStart(function(){
				var overlay = $('<div></div>').prependTo('body').attr('id', 'overlay');	
				$("#loadingDiv").show();
			}).ajaxStop(function(){
				overlay.remove();
				$("#loadingDiv").hide();
			});	
			var Book = Backbone.Model.extend({
				defaults: {
					bookname: '',
					authorname: '',
					status: '',
				},
				url : 'php/bookRequestHandler.php'
			});

			var BookList = Backbone.Collection.extend({
				model:Book,
				url : 'php/bookRequestHandler.php'
			}
			);

			var BookView = Backbone.View.extend({
				tagName: 'div',
				className: 'bookContainer',
				template: _.template( $('#bookTemplate').html() ),
				events: {
					'click .delete': 'deleteBook',
					'click .update': 'updateBook'
				},

				deleteBook: function() {
					// Fire Delete
					var self = this;
					var postData = this.model.attributes;
					postData.remove = "true";
					$.ajax({
						type: 'POST',
						url: 'php/bookRequestHandler.php',
						data: postData,
						dataType: "json",
						complete: function(){
							alert("Deleted Book : " + self.model.get("bookname"));
							self.remove();
						}
					});
				},
				
				updateBook: function() {
					// Obtain old book name (username,bookname) primary key
					var self = this;
					var oldBookName = this.model.get("bookname");
					this.$(":input").each(function(i,el){
						if(el.name)
						self.model.set(el.name,el.value);
					}
					);
					// Fire Update
					this.model.save({"oldbookname":oldBookName},{
						success : function(data){
							alert("Updated Book : " + oldBookName);
						},
						error: function(data){
							alert("Error occored while updating book, Error : " + data);
						}
					});
				},
	
				render: function() {
					this.$el.html( this.template( this.model.toJSON() ) );
					return this;
				}
			});

			var BookListView = Backbone.View.extend({
				el: '#booksDiv',
				events:{
					'click .add':'addBook'
    				},
    				
				initialize: function( bookSet ) {
					var self = this;
					this.collection = new BookList();
					this.collection.fetch({
						success:function(data){
							self.collection = data;
							self.render();
						}
					});
				},

				addBook: function() {
					var self = this;
					var book = new Book();
					$("#addBook :input").each(function(i,el){
						if(el.name)
						book.set(el.name,el.value);
					}
					);
					// Fire Update
					book.save(book.model,{
						success : function(data){
							alert("Added Book : " + book.get("bookname"));
							book.id = data;
							self.collection.add(book);
							self.renderBook(book);
						},
						error: function(data){
							alert("Error occored while adding book, Error : " + data);
						}
					});
				},
				
				// Render each book
				render: function() {
					this.collection.each(function( item ) {
						this.renderBook( item );
					}, this );
				},
	
				// Handling render on each book on its book view , and append the ref to list view
				renderBook: function( item ) {
					var bookView = new BookView({
						model: item
					});
					this.$el.append( bookView.render().el );
				}
			});
			
			var SearchBookView = Backbone.View.extend({
				el: '#searchBooksDiv',
				searchVal : {},
				events: {
					'click .search': 'searchBook'
				},

				searchBook: function() {
					// Fire Search
					var self = this;
					$(".searchVal").each(function(i,el){
						self.searchVal[el.name] = el.value;
					});
					this.collection.fetch({
						data :  self.searchVal, 
						success: function(data){
							self.initialize(data.models);
						},
						error : function(data){
							alert("Error Occured!");
						}
					});
				},
				
				initialize: function( bookSet ) {
					$("#searchBooksDiv .bookContainer").empty();
					this.collection = new BookList(bookSet);
					this.render();
				},
				
				// Render each book
				render: function() {
					this.collection.each(function( item ) {
						this.renderBook( item );
					}, this );
				},

				// Handling render on each book on its book view , and append the ref to list view
				renderBook: function( item ) {
					var bookView = new BookView({
						model: item
					});
					this.$el.append( bookView.render().el );
				}
			});
			
			var bookListView = new BookListView();	
			var searchViewInstance = new SearchBookView();	

		})(jQuery);
		</script>
		
	</body>
</html>
