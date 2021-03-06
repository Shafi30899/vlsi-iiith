<?php
// Pass Transistor File which takes the values form the Pass Transistor applet and then write the SPICE code in a file and then run it and wrutes the output to a file .
 // Wich is later read by the applet to show the graphs
echo $_POST["comp"] ;

$token = strtok($_POST["comp"] , "_");
$i = 0;
while ($token != false)
{
	$comp_value[$i++] = $token;
	$token = strtok("_");
}

$file = "inv9_ff_p.v";
$f = fopen($file , "w");
if ( $f == null )
{
	echo "File not opened  ";
}

//---------------------------------------------------
// Writing the spice code -----------------------------
//---------------------------------------------------

fwrite($f, "`timescale 1ns/1ns
 
module dff(q,clk,d,r);
	input d, clk, r ; 
	output q;

	reg q;

	//always @ ( posedge clk )
	//if (~r) begin
	  //q <= 1'b0;
	//end  else begin
	  //q <= d;
	//end
	always @ (posedge clk )
	if (~r) begin
	q <= 1'b0;
	end else begin
	q <= d;
	end
endmodule 

module dff_tb;
	wire q;
	reg d,clk,r;
	dff dff(q, clk, d,r );
	initial
		begin
	 \$monitor(q, d, clk, r,);
//   #3 d=1'b0; clk=1'b0;   #5 d=1'b0; clk=1'b1; #3 d=1'b0; clk=1'b1;   #5 d=1'b0; clk=1'b0;
	//  #3 d=1'b0; #2 d=1'b1; #3 d=1'b0; #2 d=1'b1;#2 clk=1'b0; #2 clk=1'b1;#2 clk=1'b0;#2 clk=1'b1;
   #3 d=1'b1;clk=1'b0;r=1'b1;
	#5 d=1'b1;clk=1'b1;r=1'b1;
	#3 d=1'b0;clk=1'b0;r=1'b1;
	#3 d=1'b0;clk=1'b1;r=1'b1;
	#5 d=1'b1;clk=1'b0;r=1'b1;
	#3 d=1'b1;clk=1'b1;r=1'b1;
	//#3 d=1'b1;#3 d=1'b0;#3 d=1'b1;#3 d=1'b0;#3 d=1'b1;#3 d=1'b0;
	//#2 clk=1'b1;#2 clk=1'b0;#2 clk=1'b1;#2 clk=1'b0;#2 clk=1'b1;
	//#5 r=1'b1;#5 r=1'b0;#5 r=1'b1;#5 r=1'b0;#5 r=1'b1;
	end
	always @( d or clk or r)
 #1 \$display(\"t=%t\",\$time,\" d=%b\",d,\" clk=%b\",clk,\" r=%b\",r);
endmodule
\n");


fclose($f);
echo "Done";
//-----------------------------------------------------

//`ngspice -b  inv6_flipflop_p.sp -r rawfile_flipflop_p`;

//`cat rawfile_flipflop_p |tr " "  \n" | tr "\t" "\n" >  outfile_flipflop_p`;
//`rm rawfile_flipflop_p`;


?>


