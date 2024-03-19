{
  description = "A Nix-flake-based Node.js development environment";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-23.11";
  };

  outputs = { self , nixpkgs ,... }: let
    system = "x86_64-linux";
  in {
    devShells."${system}".default = let
      pkgs = import nixpkgs {
        inherit system;
      };
    in pkgs.mkShell {
      packages = with pkgs; [
        zsh
        zsh-powerlevel10k
        (php.buildEnv {
          extensions = ({ enabled, all }: enabled ++ (with all; [
            xdebug
            imagick
            redis
          ]));
          extraConfig = ''
            xdebug.mode=debug
          '';
        })
        (php.withExtensions ({ all, enabled }:
          enabled ++ (with all; [
            imagick
            redis
          ]))
        ).packages.composer
        nodejs_20
        corepack_20
        bun
        stripe-cli
        python3
        python311Packages.cmake
      ];

      shellHook = ''
        echo "Node `${pkgs.nodejs}/bin/node --version`"
        echo "`${pkgs.php.packages.composer}/bin/composer --version`"
        echo "`${pkgs.php}/bin/php --version`"
        exec zsh
      '';
    };
  };
}
