<template>
	<el-row :gutter="0">
		<el-col :span="20" :offset="2">
			<el-form :model="registerForm" :rules="rules" ref="registerForm" >

        		<form-error v-if="error" :error="error"></form-error>

				<el-row :gutter="0">
					<el-col :span="11">
						<el-form-item label="First name" prop="firstname" required>
							<el-input v-model="registerForm.firstname" type="text"></el-input>
						</el-form-item>
        				<form-error v-if="errors.first_name" :error="errors.first_name[0]"></form-error>
					</el-col>
					<el-col class="line" :span="2">
						<label style="visibility: hidden;">-</label>
					</el-col>
					<el-col :span="11">
						<el-form-item label="Last name" prop="lastname" required>
							<el-input v-model="registerForm.lastname" type="text"></el-input>
						</el-form-item>
        				<form-error v-if="errors.last_name" :error="errors.last_name[0]"></form-error>
					</el-col>
				</el-row>
				<el-form-item label="Email" prop="email" required>
					<el-input v-model="registerForm.email" type="email"></el-input>
        			<form-error v-if="errors.email" :error="errors.email[0]"></form-error>

				</el-form-item>
				<el-form-item label="Password" prop="password" required>
					<el-input v-model="registerForm.password" type="password"></el-input>
        			<form-error v-if="errors.password" :error="errors.password[0]"></form-error>
				</el-form-item>
				<el-form-item label="Confirm password" prop="confirmpassword" required>
					<el-input v-model="registerForm.confirmpassword" type="password"></el-input>
				</el-form-item>
				<el-form-item>
					<el-button type="primary" @click="onRegister('registerForm')">Register</el-button>
				</el-form-item>
			</el-form>
		</el-col>
	</el-row>
</template>

<script>
	export default {
		props: {
			registerFirstName: String,
			registerLastName: String,
			registerEmail: String
		},
		data() {
			return {
				registerForm: {
					firstname: this.registerFirstName,
					lastname: this.registerLastName,
					email: this.registerEmail,
					password: '',
					confirmpassword: '',
				},		
				rules: {
					email: [
						{ required: true, message: 'Please input email address', trigger: 'blur' },
						{ type: 'email', message: 'Please input correct email address', trigger: 'blur,change' }
					],
					password: [
						{ required: true, message: 'Please enter your password', trigger: 'blur' },
						{ min: 6, message: 'Length should be at least 6 characters', trigger: 'blur,change' }
					],					
				},
				errors: [],
				error: ''
			}
		},
		methods: {
			onRegister(registerForm) {
				this.$refs[registerForm].validate((valid) => {
					if (valid) {
						axios.post('/api/register', {
							first_name: this.registerForm.firstname, 
							last_name: this.registerForm.lastname, 
							email: this.registerForm.email, 
							password: this.registerForm.password, 
							password_confirmation: this.registerForm.confirmpassword 
						})
						.then(response => {
							window.location = '/home';
						})
						.catch(error => {
							if (error.response.data.errors) {
								this.errors = error.response.data.errors;
							} else {
								this.error = error.response.data.message;
							}
						})
					} else {
						return false;
					}
				})
			},
			registerFormReset(registerForm) {
				this.$refs[registerForm].resetFields();
			},
		}
	}
</script>
