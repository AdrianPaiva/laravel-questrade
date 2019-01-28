<template>
	<div v-if="!isAuthorized">
		<el-button type="success" v-on:click="redirectToAuth">Authorize Questrade</el-button>
	</div>
	<div v-else>
		<p> Questrade Connected </p>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				credential: {},
			}
		},
		computed: {
			isAuthorized: function() {
				return Object.keys(this.credential).length > 0;
			}
		},
		created() {
			this.getCredential();
		},
		methods: {
			getCredential() {
				axios.get('/api/questrade-credentials/me')
				.then(response => {
					this.credential = response.data.data;
				})
				.catch(e => {
					console.log(e);
				});
			},
			redirectToAuth() {
				window.location = '/questrade/authorize';
			}
		}
	}
</script>
