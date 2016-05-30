<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="width=device-width, initia=l-scale=1.0, maximum-scale=1.0" />
			<title>{{$user->email}}, welcome to Meida Impact</title>
		</head>
		<body class="email" style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 1.429">
			<table id="background-table" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f5f5f5; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt">
			<!-- header here -->
			<tr>
				<td id="header-pattern-container" style="padding: 0px; border-collapse: collapse; padding: 10px 20px">
					<table id="header-pattern" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt">
						<tr>
							<td id="header-text-container" valign="middle" style="padding: 0px; border-collapse: collapse; vertical-align: middle; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; mso-line-height-rule: exactly; mso-text-raise: 1px">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="email-content-container" style="padding: 0px; border-collapse: collapse; padding: 0 20px">
					<table id="email-content-table" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; border-collapse: separate">
						<tr>
							<!-- there needs to be content in the cell for it to render in some clients -->
							<td class="email-content-rounded-top mobile-expand" style="padding: 0px; border-collapse: collapse; color: #fff; padding: 0 15px 0 16px; height: 15px; background-color: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; border-right: 1px solid #ccc; border-bottom: 0; border-top-right-radius: 5px; border-top-left-radius: 5px; height: 10px; line-height: 10px; padding: 0 15px 0 16px; mso-line-height-rule: exactly">&nbsp;</td>
						</tr>
						<tr>
							<td id="text-paragraph-pattern-top" class="email-content-main mobile-expand  comment-top-pattern" style="padding: 0px; border-collapse: collapse; border-left: 1px solid #ccc; border-right: 1px solid #ccc; border-top: 0; border-bottom: 0; padding: 0 15px 15px 16px; background-color: #fff; border-bottom: 1px solid #ccc; border-bottom: none; padding-bottom: 0px">
								<table class="text-paragraph-pattern" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; mso-line-height-rule: exactly; mso-text-raise: 2px">
									<tr>
										<td class="text-paragraph-pattern-container mobile-resize-text" style="padding: 0px; border-collapse: collapse; padding: 0px; padding-bottom: 10px;">
											<p style="margin: 10px 0 0 0">
												Hi {{$user->email}},
												<br />
												<br />
												Your administrator has set up an Media Impact account for you! Media Impact is the xxx solution for teams doing great work.
												<br />
												<br />
												Log in now to track your xxx.
												<br />
												<br />
												This invitation is valid for only 1 hour. If the invitation has expired, contact your administrator.
												<br />
												<br />
												Cheers,
												<br />
												The Media Impact team
											</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:0px;border-collapse:collapse;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:0;border-bottom:0;padding:0 15px 15px 16px;background-color:#fff;padding-bottom:10px">
								<table cellspacing="0" cellpadding=3D"0" border="0" style="border-collapse:collapse;color:#333">
									<tbody>
										<tr>
											<td style="background:#3068a2;border-collapse:collapse;border-radius:3px;padding:0px;margin:0px;height:30px">
												<div style="background:#3068a2;border-collapse:collapse;border-radius:3px;padding:5px;margin:0px;background:-moz-linear-gradient(top,#4687ce 0%,#3068a2 100%);background:-webkit-linear-gradient(top,#4687ce 0%,#3068a2 100%);background:-o-linear-gradient(top,#4687ce 0%,#3068a2 100%);background:-ms-linear-gradient(top,#4687ce 0%,#3068a2 100%);background:linear-gradient(top,#4687ce 0%,#3068a2 100%)">
													<a target="_blank" style="color:#3b73af;text-decoration:none;color:#fff;font-weight:bold;padding:6px;font-size:14px;line-height:1.429;font-family:Arial,sans-serif" 
														href="{!! Config::get('app.host') !!}/auth/reset/password/{!! $token !!}">
														Set my password
													</a>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<!-- there needs to be content in the cell for it to render in some clients -->
						<tr>
							<td class="email-content-rounded-bottom mobile-expand" style="padding: 0px; border-collapse: collapse; color: #fff; padding: 0 15px 0 16px; height: 5px; line-height: 5px; background-color: #fff; border-top: 0; border-left: 1px solid #ccc; border-bottom: 1px solid #ccc; border-right: 1px solid #ccc; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; mso-line-height-rule: exactly">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="footer-pattern" style="padding: 0px; border-collapse: collapse; padding: 12px 20px">
					<table id="footer-pattern-container" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt">
						<tr>
							<td id="footer-pattern-text" class="mobile-resize-text" width="100%" style="padding: 0px; border-collapse: collapse; color: #999; font-size: 12px; line-height: 18px; font-family: Arial, sans-serif; mso-line-height-rule: exactly; mso-text-raise: 2px">
								This message was sent by Media Impact
							</td>
							<td id="footer-pattern-logo-desktop-container" valign="top" style="padding: 0px; border-collapse: collapse; padding-left: 20px; vertical-align: top">
								<table style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt">
									<tr>
										<td id="footer-pattern-logo-desktop-padding" style="padding: 0px; border-collapse: collapse; padding-top: 3px"> 
											<img id="footer-desktop-logo" src="cid:footer-desktop-logo" alt="Media Impact logo" title="Media Impact logo" width="169" height="36" class="image_fix" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>