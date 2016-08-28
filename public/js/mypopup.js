  $(document).ready(function ()
        {
            $("#btnShowModal").click(function (e)
            {
                ShowDialog(true);
                e.preventDefault();
            });

            $("#btnClose").click(function (e)
            {
                HideDialog();
                e.preventDefault();
            });
            
            $("#btnCancel").click(function (e)
                    {
                        HideDialog();
                        e.preventDefault();
                    });

            $("#btnSubmit").click(function (e)
            {
            	HideDialog();
                e.preventDefault();
            	window.open("http://www.w3schools.com")
            });
        });

        function ShowDialog(modal)
        {
            $("#overlay").show();
            $("#dialog").fadeIn(300);

            if (modal)
            {
                $("#overlay").unbind("click");
            }
            else
            {
                $("#overlay").click(function (e)
                {
                    HideDialog();
                });
            }
        }

        function HideDialog()
        {
            $("#overlay").hide();
            $("#dialog").fadeOut(300);
        }