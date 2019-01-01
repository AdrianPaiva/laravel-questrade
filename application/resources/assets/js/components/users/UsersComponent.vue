<template>
	<el-table :data="users" style="width: 100%">
		<el-table-column prop="full_name" label="Name"></el-table-column>
		<el-table-column prop="role.name" label="Role"></el-table-column>
		<el-table-column prop="email" label="Email"></el-table-column>
		<el-table-column prop="created_at" label="Created On"></el-table-column>
		<el-table-column prop="updated_at" label="Last "></el-table-column>
	</el-table>
</template>

<script>
	export default {
		data() {
			return {
				users: [],
			}
		},
		created() {
			this.getUsers();
		},
		methods: {
			getUsers() {
				axios.get('/api/users?with=role')
				.then(response => {
					this.users = response.data.data;
					this.users.created_at = moment.format(this.users.created_at, 'LL');
				})
				.catch(e => {
					console.log(e);
				});
			}
		}
	}
</script>
