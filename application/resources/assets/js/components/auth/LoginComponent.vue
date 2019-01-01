<template>
	<el-row :gutter="0">
		<el-col :span="20" :offset="2">
			<h1>Welcome</h1>
			<el-form :model="loginForm" :rules="rules" ref="loginForm" @submit.native.prevent status-icon>
				<el-form-item label="Email" prop="email" required>
					<el-input v-model="loginForm.email" type="email" :disabled="isButtonDisabled" @keyup.enter.native="onLogin('loginForm')"></el-input>
				</el-form-item>
				<el-form-item label="Password" prop="password" required>
					<el-input v-model="loginForm.password" type="password" @keyup.enter.native="onLogin('loginForm')" autofocus></el-input>
				</el-form-item>
				<span v-show="loginFailed" v-text="error"></span>
				<el-form-item>
					<el-button type="primary" @keyup.enter.native="onLogin('loginForm')" @click="onLogin('loginForm')">Login</el-button>
					<a href="" style="float: right;">Forgot Password?</a>
				</el-form-item>
			</el-form>
		</el-col>
	</el-row>
</template>

<script>
	export default {
		props: {
			loginEmail: String
		},
		data() {
			return {
				isButtonDisabled: false,
				loginForm: {
					email: this.loginEmail,
					password: '',
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
				error: ''
			}
		},
		created() {
				if(!this.loginForm.email) {
					this.isButtonDisabled = false;
					this.loginForm.$refs.email.focus();
				} else {
					this.isButtonDisabled = true;
					this.loginForm.$refs.password.focus();
				}
		},
		methods: {
			onLogin(loginForm) {

				this.$refs[loginForm].validate((valid) => {
					if (valid) {
						axios.post('/login', { email: this.loginForm.email, password: this.loginForm.password })
						.then(response => {
							console.log(response);
							this.loginSuccessful(response)
						})
						.catch(error => {
							this.loginFailed(error)
						})
					} else {
						return false;
					}
				})

			},
			loginSuccessful (req) {
				if (!req.data.message) {
					this.loginFailed()
					return
				}

				window.location = '/home';
			},

			loginFailed () {
				this.error = 'Login failed!'
			}
		}
	}
</script>