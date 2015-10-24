<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Session
{
    /**
     * @var mysqli $mysqli The MySQLi object used to access the database
     * @access private
     */
    private $mysqli = null;

    /**
     * Sets the user-level session storage functions which are used
     * for storing and retrieving data associated with a session.
     *
     * @access public
     * @param  mysqli $mysqli The MySQLi object used to access the database
     * @return void
     */
    public function __construct($db)
    {
        /* assign the mysqli object */
        $this->mysqli = $db->getMysqli();
        $this->db = $db;

        ini_set('session.save_handler', 'user');

        /* set session handler to the class methods */
        session_set_save_handler(
            array(&$this, '_open'),
            array(&$this, '_close'),
            array(&$this, '_read'),
            array(&$this, '_write'),
            array(&$this, '_destroy'),
            array(&$this, '_gc')
        );

        /* start a new session */
        session_start();

        /* make sure that the session values are stored */
        register_shutdown_function('session_write_close');
    }

    /**
     * Is called to open a session. The method
     * does nothing because we do not want to write
     * into a file so we doesn't need to open one.
     *
     * @access public
     * @param  String  $save_path    The save path
     * @param  String  $session_name The name of the session
     * @return Boolean
     */
    public function _open($save_path, $session_name)
    {
        return true;
    }

    /**
     * Is called when the reading in a session is
     * completed. The method calls the garbage collector.
     *
     * @access public
     * @return Boolean
     */
    public function _close()
    {
        /* call the garbage collector */
        $this->gc(100);

        return true;
    }

    /**
     * Is called to read data from a session.
     *
     * @access public
     * @access Integer $id The id of the current session
     * @return Mixed
     */
    public function _read($id)
    {
        /* create a query to get the session data */
        $select = "SELECT
                *
            FROM
                `sessions`
            WHERE
                `id` = '" . $id . "'
            LIMIT 1;";

        /* send select statement */
        $result = $this->db->query($select);

        /* check for result */
        if (!$result) {
            throw new Exception("MySQL error while performing query.");
        }

        /* a session was found */
        if ($result->num_rows > 0) {
            /* get assoc array */
            $ret = $result->fetch_assoc();

            return $ret["value"];
        }

        /* no session found */

        return '';
    }

    /**
     * Writes data into a session rather
     * into the session record in the database.
     *
     * @access public
     * @access Integer $id The id of the current session
     * @access String $sess_data The data of the session
     * @return Boolean
     */
    public function _write($id, $sess_data)
    {
        /* check if some data was given */
        if ($sess_data == null) {
            return true;
        }

        /* query to update a session */
        $update = "UPDATE
                `sessions`
            SET
                `last_updated` = '" .time() . "',
                `value` = '" . mysqli_real_escape_string($this->mysqli, $sess_data) . "'
            WHERE
                `id` = '" . $id . "';";

        /* send select statement */
        $result = $this->db->query($update);

        /* database error */
        if ($result === false) {
            return false;
        }

        /* current session was updated */
        if ($this->mysqli->affected_rows > 0) {
            return true;
        }

        /* session does not exists create insert statement */
        $insert = "INSERT INTO
                `sessions`
                (id, last_updated, start, value)
            VALUES
                ('" . $id . "', '" . time() . "', '" . time() . "', '" . mysqli_real_escape_string($this->mysqli, $sess_data) . "');";

        /* send insert statement */
        $result = $this->db->query($insert);

        return $result;
    }

    /**
     * Ends a session and deletes it.
     *
     * @access public
     * @access Integer $id The id of the current session
     * @return Boolean
     */
    public function _destroy($id)
    {
        /* create a query to delete a session */
        $delete = "DELETE FROM
                `sessions`
            WHERE `id` = '" . $id . "';";

        /* send delete statement */
        $result = $this->db->query($delete);

        return $result;
    }

    /**
     * The garbage collector deletes all sessions from the database
     * that where not deleted by the session_destroy function.
     * so your session table will be stay clean.
     *
     * @access public
     * @access Integer $maxlifetime The maximum session lifetime
     * @return Boolean
     */
    public function _gc($maxlifetime)
    {
        /* period after that a session pass off */
        $maxlifetime = strtotime("-20 minutes");

        /* delete statement */
        $delete = "DELETE FROM
                `sessions`
            WHERE `last_updated` < '" . $maxlifetime . "';";

        /* send delete statement */
        $result = $this->db->query($delete);

        return $result;
    }
}
