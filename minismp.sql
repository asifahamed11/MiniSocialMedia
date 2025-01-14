-- Table structure for table `comments`
CREATE TABLE `comments` (
  `Id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Post_id` int(11) NOT NULL,
  `Content` text NOT NULL,
  `Created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `likes`
CREATE TABLE `likes` (
  `Id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `posts`
CREATE TABLE `posts` (
  `Id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Content` text NOT NULL,
  `Created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `users`
CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
