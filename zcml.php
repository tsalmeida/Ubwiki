<!DOCTYPE html>
<!--
 ________   ____              __
/\_____  \ /\  _`\    /'\_/`\/\ \
\/____//'/'\ \ \/\_\ /\      \ \ \
     //'/'  \ \ \/_/_\ \ \__\ \ \ \  __
    //'/'___ \ \ \L\ \\ \ \_/\ \ \ \L\ \
    /\_______\\ \____/ \ \_\\ \_\ \____/
    \/_______/ \/___/   \/_/ \/_/\/___/

@author Stephen Rhyne  Note:(Micro Templating by John Resig)
@twitter srhyne
@email stephen@stephenrhyne.com
@license Do whatever you want with this..
-->
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>ZCML Sample</title>
		<style type="text/css" media="screen">
			table{
				margin:0px auto;
				border-collapse:collapse;
				margin-bottom:50px;
				-webkit-box-shadow:0px 2px 3px rgba(0,0,0,.3);
			}
			table th, table td{
				padding:3px 3px 3px 6px;
				text-shadow:0px 1px 1px rgba(0,0,0,.2);
			}
			table th{
				background:#e1e1e1;
				border-bottom:1px solid rgba(0,0,0,.1);
			}
			table td{
				background:#eee;
				border-top:1px solid rgba(255,255,255,.8);
				border-bottom:1px solid rgba(0,0,0,.1);
			}
		</style>
	</head>

	<!-- Using router firmware data from http://www.sputnik.com/support/download/ -->
	<body>
		<table>
			<thead>
				<tr>
					<th>Download</th>
					<th>Requires DDWRT Activate</th>
					<th>Flash Method</th>
					<th>Type</th>
					<th>Import ID</th>
					<th>Agent Version</th>
					<th>Subtype</th>
					<th>Sputnik Default</th>
					<th>Version</th>
				</tr>
			</thead>

			<script type="text/zcml" elName="zc-component" id="firmwares" viewLinkName="firmware_list" params=""
				appLinkName="sputnik" sharedBy="sputnikwifi" adminuser="sputnik"
				privateLink="hfnjhD5N1RsSNyMmfB4CSVxgkD8gx3aOwyeN2St43uSt6zSfpXPt8VU52s8w8K8huXkTrX1JCaqtFPgWOYADrBNyy6F2UbYZUu56">
				<tr>
					<td><a href="<%=download%>">Download</a></td>
					<%if(!requires_ddwrt_activate)
						{%>
							<td>No</td>
					  <%}
					else
						{%>
							<td><%=requires_ddwrt_activate%></td>
					  <%}%>
					<td><%=flash_method%></td>
					<td><%=type%></td>
					<td><%=import_id%></td>
					<td><%=agent_version%></td>
					<td><%=subtype%></td>
					<td><%=sputnik_default%></td>
					<td><%=version%></td>
				</tr>
			</script>

		</table>

		<!-- jquery CDN -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
		<!-- zcml engine -->
		<script src="zcml.min.js" type="text/javascript" charset="utf-8"></script>

	</body>
</html>
