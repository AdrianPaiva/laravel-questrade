<template>
	<div v-if="loginType === 'welcome'">
		<el-row :gutter="0">
			<el-col :span="20" :offset="2">
				<h1>Welcome</h1>
				<el-form :model="welcomeForm" ref="welcomeForm" :rules="rules" :label-position="labelPosition" label-width="80px" @submit.native.prevent status-icon>
					<el-form-item label="Email" :error="errorData.verifyError" required>
						<el-input placeholder="Please enter your email" v-model="welcomeForm.email" autofocus @keyup.enter.native="verifyEmail"></el-input>
					</el-form-item>
					<el-form-item>
						<el-button type="primary" @click="verifyEmail" :loading="loading">Next</el-button>
					</el-form-item>
				</el-form>
			</el-col>
		</el-row>
	</div>
	<div v-else-if="loginType === 'register'">
		<register-component :registerFirstName="welcomeForm.first_name" :registerLastName="welcomeForm.last_name" :registerEmail="welcomeForm.email"></register-component>
	</div>
	<div v-else-if="loginType === 'login'">	
		<login-component :loginEmail="welcomeForm.email"></login-component>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				loading: false,
				labelPosition: 'top',
				loginType: 'welcome',
				welcomeForm: {
					first_name: '',
					last_name: '',
					email: ''
				},
				errorData: {
					verifyError: '',
				},
				rules: {
					email: [
						{required: true, message: 'Email is required', trigger: 'blur' },
						{ type: 'email', message: 'Please enter valid email address', trigger: 'blur'}
					]
				},
			}
		},
		methods: {
			verifyEmail() {
				this.loading = true;
				axios.get('api/register/verify-user-exists/'+this.welcomeForm.email)
				.then(response => {
					if (!response.data.data.id) {
						this.loginType = 'register';
					} else if(response.data.data.id) {
						this.loginType = 'login';
						this.$refs.loginForm.password.focus();
					} else {
						this.loading = false;
						this.errorData.verifyError = response.data.message;
					}
				})
				.catch(error => {
					console.log(error.data.data.message);
				});
			}
		}		
	}
</script>

<style scoped>
	
</style>
