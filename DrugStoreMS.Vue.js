//This is a Vue application to manage a drug store built using the laravel framework

//The main page which controls all components of the system

/*
	This page takes care of keeping track of all the sections or components of the application by giving them special names(variables) which he can easily call them when needed.
	The vue-router is responsible for telling the page where to find the section it needs when it calls it, where it is located exactly.

	It also sets the section to be displayed each time a user opens the application, in this case its home
*/
window.Vue = require('vue');

import VueRouter from 'vue-router'

Vue.use(VueRouter)

let navbar = require('./components/layout/navbar.vue');
let Myfooter = require('./components/layout/Myfooter.vue');

let Home = require('./components/Home.vue');
let Patient = require('./components/patients/Patient.vue');

let category = require('./components/drugs/category')
let age_group = require('./components/drugs/age_group')

const app = new Vue({
    el: '#app',

    data: {
        home: true,
        category:false,
        agegroup:false
    },

    components:{navbar,Home,Myfooter,category,age_group} 
});


/*The various templates represent different components in a single-page drug store management system
When writing html in vue in laravel we enclose it in a template tag so vue can render it
*/

/*
this template serves as a drugs page with a button which opens a form to register new drugs.
It displays list of all drugs that have been registered in a table
*/
<template>
	<div>
		<div class="columns">
			<div class="column is-10 is-offset-1">
				<div class="column is-pulled-right">
					<button class="button is-info" @click="openDrugs"><i class="fa fa-medkit"></i>&nbsp; New Drug</button>
					<button class="button is-info"><i class="fa fa-medkit"></i>&nbsp; Category</button>
					<button class="button is-info"><i class="fa fa-medkit"></i>&nbsp; Age group</button>
				</div>
				<div class="column">
					<h1 class="title">Manage Drugs</h1>
				</div>
				<hr>
				<table class="table is-fullwidth is-narrow is-striped is-hoverable">
					<thead>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>UnitPrice</th>
							<th>Age-group</th>
							<th>Category</th>
							<th>Added On</th>
						</tr>
					</thead>
					<tbody>
						<div class="notification is-warning">
							You have no Registered Drugs
						</div>
						<tr v-for="item in lists" :Key="item.id">
							<td>{{item.name}}</td>
							<td>{{item.description}}</td>
							<td>{{item.unitprice}}</td>
							<td>{{item.ageGroup_id}}</td>
							<td>{{item.category_id}}</td>
							<td>{{item.created_at}}</td>
							<td><button class="button is-small is-danger" @click="remove(item)"><i class="fa fa-trash"></i></button></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<Drugs :openmodal = 'addActive' @closeRequest = 'close'></Drugs>
	</div>
</template>

/*This script is responsible for controlling the template, that is, any request from the template like the registering of drugs, displaying of the template and the fetching of all registered drugs from the database table
*/
<script>
	
	//getting the drugs template and displaying on the page
	let Drugs = require('./drugs/Drugs.vue');

	export default{
		components:{Drugs},

		/*after the page has been displayed, all the drugs are gotten from database and displayed*/
		data(){
			return{
				addActive: '',
				lists:{},
				errors:{}, 
			}
		},
		mounted(){
			axios.post('/getData').
			then((response)=> this.lists = response.data)
			.catch((error)=> this.errors = error.response.data.errors)
		},
		//methods holds all the operations that handle opening the register form closing the form and deleting a drug from the database
		methods:{
			openDrugs(){
				this.addActive = 'is-active';
			},

			close(){
				this.addActive = ''
			},
			remove(item){
				axios.delete(`/drugstore/${item.id}`)
				.then((res)=> {
					const itemIndex = this.lists.indexOf(item)
					this.lists.splice(itemIndex, 1)
				})
				.catch((err)=> {
					console.log(err)
				})
			}
		}
	}
</script>


/*
This template actually holds the form to register a drug and it is a pop-up box which displays when you click on the create button from the previous template 
*/
<template>
	
	<div class="modal" :class='openmodal'>

		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">New Drug</p>
				<button class="delete" aria-label="close" @click = 'close'></button>
			</header>

			<section class="modal-card-body">
				<div class="field">
					<label class="label">Name</label>
					<div class="control">
						<input type="text" class="input" :class="{'is-danger':errors.name}" name="name" placeholder="Name" v-model="list.name">
					</div>
					<small v-if="errors.name" class="has-text-danger">{{errors.name[0]}}</small>
				</div>
				<div class="field">
					<label for="#">Unit Price</label>
					<input type="text" class="input" name="unitprice" placeholder="Unit Price" v-model="list.unitprice">
				</div>
				<div class="field">
					<label for="#">Description</label>
					<input type="text" class="input" name="description" placeholder="Description" v-model="list.description">
				</div>
				<div class="field">
					<label for="#">Category</label>
					<select name="category_id" class="select is-multiple" id="" v-model="list.category_id">
						<option v-for="category in categories" :Key="category.id" :value="category.id">{{category.name}}</option>
					</select>
				</div>
				<div class="field">
					<label for="#">Age Group</label>
					<select name="ageGroup_id" class="select" v-model="list.ageGroup_id">
						<option  v-for="agegroup in agegroups" :Key="agegroup.id" :value="agegroup.id">{{agegroup.name}}</option>
					</select>
				</div>
			</section>

			<footer class="modal-card-foot"> 
				<button class="button is-info" @click="Add">Add</button>
				<button class="button" @click = "close">Cancel</button>
			</footer>
		</div>
	</div>
</template>

/*
This script is responsible for taking care of all the data entered into the form when the add button is pressed. It takes all that data and checks it if its ok then saves in the database using axios.
If in case you didnt fill a field in the form it alerts you to fill before it saves the data
*/
<script>
	export default{
		props: ['openmodal'],

		data(){
			return{
				list:{
					name:'',
					unitprice:'',
					description:''
				},
				agegroups:{},
				categories:{},
				errors:{}
			}
		},
		
		methods:{
			close(){
				this.$emit('closeRequest')
			},
			Add(){
				axios.post('/drugstore',this.$data.list).then((response)=> {
					this.close()
					this.$parent.lists.push($this.data.lists)
				})
				.catch((error)=> this.errors = error.response.data.errors)
			},
		}
	}
</script>
